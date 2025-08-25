<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailJob;
use App\Models\ApiProvider;
use App\Models\Newsletter;
use App\Models\Order;
use App\Models\Setting;
use App\Services\ApiProviderService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function __construct(private ApiProviderService $providerService) {}

    /**
     * Handle cron jobs
     */
    public function handle(Request $request)
    {
        $this->processOrders($request);
        $this->updateOrderStatus($request);
        $this->orderRefillStatus($request);
        $this->updateDripFeedStatus($request);
        $this->updateSubscriptionStatus($request);
        $this->sendScheduledEmail($request);

        if (! cache()->has('cron_db_updated')) {
            $general = Setting::first();
            $general->last_cron = now();
            $general->save();

            // prevent DB update for next 10 minutes
            cache()->put('cron_db_updated', true, now()->addMinutes(15));
        }

        return 'success';
    }

    /**
     * Process Orders to api providers
     */
    public function processOrders(Request $request)
    {
        $orders = Order::with('provider')
            ->where('type', 'api')
            ->where('api_order_id', '<', 0)
            ->where('status', 'pending')
            ->where('error', 0)
            ->where('updated_at', '<', now()->subMinutes(5))
            ->limit(500)
            ->get();
        if ($orders->isEmpty()) {
            echo 'No orders found to process <br>';

            return 'No orders found to process';
        }
        foreach ($orders as $order) {
            $provider = $order->provider;
            if (! $provider) {
                $response = ['error' => 'API Provider does not exists'];
                $order->error = 1;
                $order->error_message = $response['error'];
                $order->response = $response;
                $order->save();
            }
            // get request data
            $requestData = [
                'action' => 'add',
                'service' => $order->api_service_id,
            ];

            // add based on service type
            switch ($order->service_type) {
                case 'subscriptions':
                    $requestData['username'] = $order->username;
                    $requestData['min'] = $order->sub_min;
                    $requestData['max'] = $order->sub_max;
                    $requestData['posts'] = ($order->sub_posts == -1) ? 0 : $order->sub_posts;
                    $requestData['delay'] = $order->sub_delay;
                    $requestData['expiry'] = (! empty($order->sub_expiry)) ? date('d/m/Y', strtotime($order->sub_expiry)) : ''; // change date format dd/mm/YYYY
                    break;

                case 'custom_comments':
                    $requestData['link'] = $order->link;
                    $requestData['comments'] = ($order->comments);
                    break;

                case 'mentions_with_hashtags':
                    $requestData['link'] = $order->link;
                    $requestData['quantity'] = $order->quantity;
                    $requestData['usernames'] = $order->usernames;
                    $requestData['hashtags'] = $order->hashtags;
                    break;

                case 'mentions_custom_list':
                    $requestData['link'] = $order->link;
                    $requestData['usernames'] = ($order->usernames);
                    break;

                case 'mentions_hashtag':
                    $requestData['link'] = $order->link;
                    $requestData['quantity'] = $order->quantity;
                    $requestData['hashtag'] = $order->hashtag;
                    break;

                case 'mentions_user_followers':
                    $requestData['link'] = $order->link;
                    $requestData['quantity'] = $order->quantity;
                    $requestData['username'] = $order->username;
                    break;

                case 'mentions_media_likers':
                    $requestData['link'] = $order->link;
                    $requestData['quantity'] = $order->quantity;
                    $requestData['media'] = $order->media;
                    break;

                case 'package':
                    $requestData['link'] = $order->link;
                    break;

                case 'custom_comments_package':
                    $requestData['link'] = $order->link;
                    $requestData['comments'] = ($order->comments);
                    break;

                case 'comment_likes':
                    $requestData['link'] = $order->link;
                    $requestData['quantity'] = $order->quantity;
                    $requestData['username'] = $order->username;
                    break;

                default:
                    $requestData['link'] = $order->link;
                    $requestData['quantity'] = $order->quantity;
                    if ($order->is_drip_feed) {
                        $requestData['runs'] = $order->runs;
                        $requestData['interval'] = $order->interval;
                        $requestData['quantity'] = $order->dripfeed_quantity;
                    } else {
                        $requestData['quantity'] = $order->quantity;
                    }
                    break;
            }

            $response = $this->providerService->sendOrders($provider, $requestData);
            if (isset($response['order']) && $response['order']) {
                $order->api_order_id = $response['order'];
                $order->status = 'processing';
                $order->response = $response;
                $order->save();
            } else {
                $order->api_order_id = $response['order'] ?? null;
                $order->response = $response;
                $order->error = 1;
                $order->error_message = $response['error'] ?? '';
                $order->save();
            }
        }

        return 'success';
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request)
    {
        $orders = Order::whereIn('status', ['active', 'processing', 'inprogress', 'pending'])
            ->where('api_order_id', '>', 0)
            ->where('updated_at', '<', now()->subMinutes(5))
            ->where('service_type', '!=', 'subscriptions')
            ->where('is_drip_feed', '!=', true)
            ->limit(500)
            ->get();

        if ($orders->isEmpty()) {
            echo 'No orders found to update. <br>';

            return 'No orders found to update.';
        }

        $ordersByProvider = $orders->groupBy('api_provider_id');

        foreach ($ordersByProvider as $providerId => $ordersGroup) {
            $provider = ApiProvider::find($providerId);
            if (! $provider) {
                continue;
            }

            $response = $this->providerService->multipleStatus($provider, $ordersGroup->pluck('api_order_id')->toArray());
            if ($response && is_array($response)) {
                $response = collect($response)->keyBy('order')->toArray();
                foreach ($ordersGroup as $order) {
                    if (isset($response[$order->api_order_id])) {
                        $apiOrderData = $response[$order->api_order_id];
                        echo "Processing Order ID: {$order->id}... <br>";

                        if (isset($apiOrderData['error'])) {
                            $order->note = $apiOrderData['error'];
                            echo "Error: {$apiOrderData['error']}<br>";
                        }

                        if (isset($apiOrderData['status'])) {
                            $received_status = strtolower(str_replace(' ', '', $apiOrderData['status']));

                            $order->status = $received_status;
                            $order->start_counter = $apiOrderData['start_count'] ?? $order->start_counter;
                            $order->remains = $apiOrderData['remains'] ?? $order->remains;

                            if (in_array($received_status, ['partial', 'canceled', 'refunded'])) {
                                $order->load('user');

                                if ($order->status != 'refunded') {
                                    $quantity = $order->quantity;
                                    $remains = $order->remains;
                                    $price = $order->price;

                                    if ($received_status == 'partial' && $quantity > 0 && $remains > 0) {
                                        $refundAmount = ($price / $quantity) * $remains;
                                    } else {
                                        $refundAmount = $price;
                                    }

                                    if ($refundAmount > 0) {
                                        $user = $order->user;
                                        $user->balance += $refundAmount;
                                        $user->save();
                                        echo "Refunded {$refundAmount} to user {$user->id}. <br>";
                                    }
                                }
                                $order->status = $received_status;
                                $order->save();
                            }
                            echo "Status updated to {$received_status}.<br>";
                        }

                        $order->response = $apiOrderData ?? [];
                        $order->save();
                    }
                }
            }
        }

        return 'Status update process completed.';
    }

    /**
     * Order refill status
     */
    public function orderRefillStatus(Request $request)
    {
        $orders = Order::with('service.provider')
            ->whereNotIn('refill_status', ['completed', 'refunded', 'canceled', 'rejected'])
            ->whereNotNull('api_refill_id')
            ->whereHas('service.provider')
            ->get();

        if ($orders->isEmpty()) {
            echo 'No pending refills to check. <br>';

            return 'No pending refills to check.';
        }

        $ordersByProvider = $orders->groupBy('service.provider.id');

        foreach ($ordersByProvider as $providerId => $ordersGroup) {
            if (! $providerId) {
                continue;
            }

            $provider = $ordersGroup->first()->service->provider;

            $response = $this->providerService->multipleRefillStatus(
                $provider,
                $ordersGroup->pluck('api_refill_id')->toArray()
            );

            if ($response && is_array($response)) {
                $response = collect($response)->keyBy('refill')->toArray();
                foreach ($ordersGroup as $order) {
                    if (isset($response[$order->api_refill_id])) {
                        $refillData = $response[$order->api_refill_id];
                        if (isset($refillData['status'])) {
                            $order->refill_status = formatOrderStatus($refillData['status']);
                            $order->status = formatOrderStatus($refillData['status']);
                            // $order->save();
                            echo "Refill for Order #{$order->id} updated to: {$order->refill_status} <br>";
                        }
                        $order->response = $refillData ?? [];
                        $order->save();
                    }
                }
            }
        }

        return 'Refill status check complete.';
    }

    /**
     * Send scheduled email
     */
    public function sendScheduledEmail(Request $request)
    {
        $currentDateTime = Carbon::now();
        $newsletters = Newsletter::where('status', 2)
            ->where('date', '<=', $currentDateTime)
            ->get();

        foreach ($newsletters as $newsletter) {
            if ($newsletter->user_emails) {
                $delayMinutes = rand(1, 10);
                dispatch(new SendMailJob($newsletter))->delay(now()->addMinutes($delayMinutes));
            }

            $otherEmails = array_filter(array_map('trim', explode(',', $newsletter->other_emails)));
            $delayMinutes = rand(1, 10);
            foreach ($otherEmails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    dispatch(new SendMailJob($newsletter, $email))->delay(now()->addMinutes($delayMinutes));
                }
            }
            $newsletter->status = 1;
            $newsletter->save();
        }
    }

    /**
     * Update Drip-Feed order status
     */
    public function updateDripFeedStatus(Request $request)
    {
        // Get active Drip-Feed orders
        $orders = Order::with('service.provider')
            ->where('is_drip_feed', true)
            ->whereIn('status', ['active', 'processing', 'inprogress'])
            ->whereNotNull('api_order_id')
            ->where('updated_at', '<', now()->subMinutes(5))
            ->limit(100)
            ->get();

        if ($orders->isEmpty()) {
            echo 'No active Drip-Feed orders found. <br>';

            return 'No active Drip-Feed orders found.';
        }

        $ordersByProvider = $orders->groupBy('service.provider.id');

        foreach ($ordersByProvider as $providerId => $ordersGroup) {
            if (! $providerId || ! $ordersGroup->first()->service->provider) {
                continue;
            }

            $provider = $ordersGroup->first()->service->provider;
            $response = $this->providerService->multipleStatus($provider, $ordersGroup->pluck('api_order_id')->toArray());

            if ($response && is_array($response)) {
                foreach ($ordersGroup as $order) {
                    if (isset($response[$order->api_order_id])) {
                        $dripData = $response[$order->api_order_id];

                        $order->status = strtolower($dripData['status']);
                        $order->runs = $dripData['runs'] ?? $order->runs;
                        $order->save();

                        if (! empty($dripData['orders'])) {
                            $this->createChildOrdersFromProvider($order, $dripData['orders']);
                        }
                        echo "Drip-Feed #{$order->id} status updated to: {$order->status}. <br>";
                    }
                }
            }
        }

        return 'Drip-Feed status check complete.';
    }

    /**
     * Update Subscription order status
     */
    public function updateSubscriptionStatus(Request $request)
    {
        // Get active Subscription orders
        $orders = Order::with('service.provider')
            ->where('service_type', 'subscriptions')
            ->whereIn('sub_status', ['Active', 'Paused'])
            ->whereNotNull('api_order_id')
            ->where('updated_at', '<', now()->subMinutes(5))
            ->limit(100)
            ->get();

        if ($orders->isEmpty()) {
            return 'No active Subscription orders found.';
        }

        $ordersByProvider = $orders->groupBy('service.provider.id');

        foreach ($ordersByProvider as $providerId => $ordersGroup) {
            if (! $providerId || ! $ordersGroup->first()->service->provider) {
                continue;
            }

            $provider = $ordersGroup->first()->service->provider;
            $response = $this->providerService->multipleStatus($provider, $ordersGroup->pluck('api_order_id')->toArray());

            if ($response && is_array($response)) {
                foreach ($ordersGroup as $order) {
                    if (isset($response[$order->api_order_id])) {
                        $subData = $response[$order->api_order_id];

                        $order->sub_status = $subData['status'];
                        $order->sub_posts = $subData['posts'] ?? $order->sub_posts;
                        if (in_array($order->sub_status, ['Completed', 'Expired', 'Canceled'])) {
                            $order->status = strtolower($order->sub_status);
                        }

                        $order->save();

                        if (! empty($subData['orders'])) {
                            $this->createChildOrdersFromProvider($order, $subData['orders']);
                        }
                        echo "Subscription #{$order->id} status updated to: {$order->sub_status}. <br>";
                    }
                }
            }
        }

        return 'Subscription status check complete.';
    }

    private function createChildOrdersFromProvider(Order $parentOrder, array $providerOrderIds)
    {
        $existingApiOrderIds = Order::where('parent_order_id', $parentOrder->id)
            ->pluck('api_order_id')
            ->all();

        $newApiOrderIds = array_diff($providerOrderIds, $existingApiOrderIds);

        if (empty($newApiOrderIds)) {
            return;
        }

        $newOrdersData = [];
        foreach ($newApiOrderIds as $apiOrderId) {
            $charge = 0;
            $apiPrice = 0;

            if ($parentOrder->is_drip_feed && $parentOrder->runs > 0) {
                $charge = $parentOrder->price / $parentOrder->runs;
                $apiPrice = $parentOrder->api_price / $parentOrder->runs;
            } elseif ($parentOrder->service_type === 'subscriptions' && $parentOrder->sub_posts > 0) {
                $charge = $parentOrder->price / $parentOrder->sub_posts;
                $apiPrice = $parentOrder->api_price / $parentOrder->sub_posts;
            } else {
                \Log::warning("Cannot calculate charge for parent order {$parentOrder->id}: runs or sub_posts is zero");

                return;
            }

            $newOrdersData[] = [
                'user_id' => $parentOrder->user_id,
                'service_id' => $parentOrder->service_id,
                'api_provider_id' => $parentOrder->api_provider_id,
                'api_service_id' => $parentOrder->api_service_id,
                'parent_order_id' => $parentOrder->id,
                'api_order_id' => $apiOrderId,
                'type' => 'api',
                'service_type' => 'default',
                'status' => 'pending',
                'link' => $parentOrder->link,
                'quantity' => $parentOrder->is_drip_feed ? $parentOrder->dripfeed_quantity : ($parentOrder->sub_max ?? 0),
                'remains' => $parentOrder->is_drip_feed ? $parentOrder->dripfeed_quantity : ($parentOrder->sub_max ?? 0),
                'price' => $charge,
                'api_price' => $apiPrice,
                'profit' => $charge - $apiPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (! empty($newOrdersData)) {
            Order::insert($newOrdersData);
            echo 'Created '.count($newOrdersData)." child orders for Parent #{$parentOrder->id}. <br>";
        }
    }
}
