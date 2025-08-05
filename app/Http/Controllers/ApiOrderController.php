<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Services\OrderService;
use Carbon\Carbon;
use Http;
use Illuminate\Http\Request;
use Validator;

class ApiOrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    /**
     * Handle API requests
     */
    public function process(Request $request)
    {
        if (! $request->action) {
            return response()->json(['error' => 'The action field is required']);
        }

        if (! $request->key) {
            return response()->json(['error' => 'The api key field is required']);
        }
        $actionExists = ['services', 'add', 'status', 'refill', 'refill_status', 'balance', 'cancel'];

        $action = $request->action;
        if (! method_exists($this, $action) || ! in_array($action, $actionExists)) {
            return response()->json(['error' => 'Invalid action']);
        }
        $user = User::where('api_token', $request->key)->first();
        if (! $user) {
            return response()->json(['error' => 'Invalid api key']);
        }

        return $this->$action($user, $request);
    }

    /**
     * Get User Balance
     */
    private function balance($user, $request)
    {
        $result = [
            'status' => 'success',
            'balance' => ($user->balance),
            'currency' => get_setting('currency_code'),
        ];

        return response()->json($result);
    }

    /**
     * List of services
     */
    private function services()
    {
        $services = Service::whereStatus(1)->with('category')->orderBy('category_id')->get();
        $modifyService = [];

        foreach ($services as $service) {
            $modifyService[] = [
                'service' => $service->id,
                'name' => $service->name,
                'category' => $service->category?->name ?? null,
                'rate' => $service->price,
                'min' => $service->min,
                'max' => $service->max,
                'type' => $service->type,
                'desc' => $service->description,
                'dripfeed' => $service->dripfeed,
                'refill' => $service->refill,
                'cancel' => $service->cancel,
            ];
        }

        return response()->json($modifyService, 200);
    }

    /**
     * Order status
     */
    public function status($user, $request)
    {
        if ($request->order) {

            $order = Order::where('id', $request['order'])->where('user_id', $user->id)->with('service')->first();
            if (! $order) {
                return response()->json(['error' => 'The selected order id is invalid.'], 422);
            }

            $result['status'] = $order->status;
            $result['charge'] = $order->service->price;
            $result['start_count'] = (int) $order->start_counter;
            $result['remains'] = (int) $order->remains;
            $result['currency'] = get_setting('currency_code');

            return response()->json($result, 200);
        } elseif ($request->orders) {
            // multi orders
            return $this->orders($user, $request);
        } else {
            return response()->json(['error' => 'The order field is required']);
        }
    }

    /**
     * List Orders
     */
    public function orders($user, $request)
    {
        $orders = explode(',', $request['orders']);
        $result = Order::whereIn('id', $orders)->where('user_id', $user->id)->with('service')->get()->map(function ($order) {
            return [
                'order' => $order->id,
                'status' => $order->status,
                'charge' => $order->service->price,
                'start_count' => (int) $order->start_counter,
                'remains' => (int) $order->remains,
                'currency' => get_setting('currency_code'),
            ];
        });

        return response()->json($result, 200);
    }

    /**
     * Place order
     */
    public function add($user, $request)
    {
        $service = Service::where('id', $request->service)->where('status', 1)->first();
        if (! $service) {
            return response()->json(['error' => 'Invalid service ID'], 422);
        }
        $rules = ['service' => 'required|exists:services,id'];

        switch ($service->type) {
            case 'custom_comments':
                $rules['link'] = 'required|url';
                $rules['comments'] = 'required|string|min:1';
                break;

            case 'mentions_custom_list':
                $rules['link'] = 'required|url';
                $rules['usernames'] = 'required|string|min:1';
                break;

            case 'mentions_with_hashtags':
                $rules['link'] = 'required|url';
                $rules['quantity'] = "required|integer|min:{$service->min}|max:{$service->max}";
                $rules['usernames'] = 'required|string';
                $rules['hashtags'] = 'required|string';
                break;

            case 'mentions_hashtag':
                $rules['link'] = 'required|url';
                $rules['quantity'] = "required|integer|min:{$service->min}|max:{$service->max}";
                $rules['hashtag'] = 'required|string';
                break;

            case 'mentions_user_followers':
            case 'comment_likes':
                $rules['link'] = 'required|url';
                $rules['quantity'] = "required|integer|min:{$service->min}|max:{$service->max}";
                $rules['username'] = 'required|string';
                break;

            case 'mentions_media_likers':
                $rules['link'] = 'required|url';
                $rules['quantity'] = "required|integer|min:{$service->min}|max:{$service->max}";
                $rules['media_url'] = 'required|url';
                break;

            case 'package':
            case 'custom_comments_package':
                $rules['link'] = 'required|url';
                if ($service->type === 'custom_comments_package') {
                    $rules['comments'] = 'required|string|min:1';
                }
                break;

            case 'subscriptions':
                $rules['username'] = 'required|string|min:3';
                $rules['posts'] = 'required|integer|min:1';
                $rules['min'] = "required|integer|min:{$service->min}";
                $rules['max'] = "required|integer|gte:min|max:{$service->max}";
                $rules['delay'] = 'required|integer|in:0,5,10,15,30,60,90';
                $rules['expiry'] = 'nullable|date';
                break;

            default:
                $rules['link'] = 'required|url';
                $rules['quantity'] = "required|integer|min:{$service->min}|max:{$service->max}";
                break;
        }

        if ($service->drip_feed && filter_var($request->dripfeed, FILTER_VALIDATE_BOOLEAN)) {
            $rules['runs'] = 'required|integer|min:2';
            $rules['interval'] = 'required|integer|min:1';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $data = [
            'link' => $request->link,
            'quantity' => $request->quantity,
            'comments' => $request->comments,

            'is_drip_feed' => filter_var($request->dripfeed, FILTER_VALIDATE_BOOLEAN),
            'runs' => $request->runs,
            'interval' => $request->interval,
            'usernames' => $request->usernames,
            'usernames_custom' => $request->usernames,
            'hashtags' => $request->hashtags,
            'hashtag' => $request->hashtag,
            'username' => $request->username,
            'media_url' => $request->media_url,

            'sub_username' => $request->username,
            'sub_posts' => $request->posts,
            'sub_min' => $request->min,
            'sub_max' => $request->max,
            'sub_delay' => $request->delay,
            'sub_expiry' => $request->expiry,
        ];
        try {

            $order = $this->orderService->createOrder(
                $user,
                $service,
                $data
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Your order has been placed successfully!',
                'order' => $order->id,
            ], 200);
        } catch (InsufficientBalanceException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to place order. Please try again.'], 422);
        }
    }

    /**
     * Refill order
     */
    public function refill($user, $request)
    {
        if (! empty($request['refill'])) {
            // Single refill request
            return $this->processRefill($user, $request['refill']);
        }

        if (! empty($request['refills'])) {
            // Multiple refill requests
            $refillIds = explode(',', $request['refills']);
            $response = [];

            foreach ($refillIds as $refillId) {
                $refillId = (int) trim($refillId);
                $result = $this->processRefill($user, $refillId, true); // true for multiple mode
                $response[] = $result;
            }

            return response()->json($response, 200);
        }

        return response()->json(['error' => 'Refill or refills parameter is required.'], 422);
    }

    private function processRefill($user, $orderId, $multiple = false)
    {
        $order = Order::with('service', 'service.provider')
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        $responseData = ['refill' => (int) $orderId];

        if (! $order) {
            $status = ['error' => 'The selected order id is invalid.'];

            return $multiple ? array_merge($responseData, ['status' => $status]) : response()->json(['error' => $status['error']], 422);
        }

        $canRefill = $order->status === 'completed' &&
            $order->remains > 0 &&
            optional($order->service)->refill != 3 &&
            (! $order->refilled_at || $order->refilled_at->lt(Carbon::now()->subHours(24))) &&
            (
                ! isset($order->refill_status) ||
                in_array($order->refill_status, ['completed', 'partial', 'canceled', 'refunded'])
            );

        if (! $canRefill) {
            $status = ['error' => 'You are not eligible to send refill request.'];

            return $multiple ? array_merge($responseData, ['status' => $status]) : response()->json(['error' => $status['error']], 400);
        }

        if (optional($order->service)->refill == 2) {
            if (optional(optional($order->service)->provider)->status != 1) {
                $status = ['error' => 'You are not eligible to send refill request.'];

                return $multiple ? array_merge($responseData, ['status' => $status]) : response()->json(['error' => $status['error']], 400);
            }

            $refillResponse = Http::post(optional($order->service)->provider->url, [
                'key' => optional(optional($order->service)->provider)->api_key,
                'action' => 'refill',
                'order' => $order->api_order_id,
            ]);

            if (! isset($refillResponse['refill'])) {
                $status = ['error' => 'You are not eligible to send refill request.'];

                return $multiple ? array_merge($responseData, ['status' => $status]) : response()->json(['error' => $status['error']], 400);
            }

            $order->api_refill_id = $refillResponse['refill'];
        }

        $order->refill_status = 'awaiting';
        $order->refilled_at = now();
        $order->save();

        if ($multiple) {
            return array_merge($responseData, ['status' => 'success']);
        } else {
            return response()->json(['status' => 'success', 'refill' => $order->id], 200);
        }
    }

    /**
     * Refill status
     */
    public function refill_status($user, $request)
    {
        if (! empty($request['refill'])) {
            $order = Order::where('id', $request['refill'])->where('user_id', $user->id)->whereNotNull('refill_status')->first();

            if (! $order) {
                return response()->json(['status' => ['error' => 'Refill not found']], 200);
            }

            return response()->json(['status' => ucfirst($order->refill_status)], 200);
        }

        if (! empty($request['refills'])) {
            $refillIds = explode(',', $request['refills']);
            $response = [];

            foreach ($refillIds as $refillId) {
                $refillId = (int) trim($refillId);
                $data = ['refill' => $refillId];

                $order = Order::where('id', $refillId)->where('user_id', $user->id)
                    ->whereNotNull('refill_status')
                    ->first();

                if (! $order) {
                    $data['status'] = ['error' => 'Refill not found'];
                } else {
                    $data['status'] = ucfirst($order->refill_status);
                }

                $response[] = $data;
            }

            return response()->json($response, 200);
        }

        return response()->json(['error' => 'Refill or refills parameter is required.'], 422);
    }

    /**
     * Cancel order
     */
    public function cancel($user, $request)
    {
        if (! empty($request->order)) {
            $order = Order::where('id', $request->order)->where('user_id', $user->id)->with('service')->first();

            $orderData = ['order' => (int) $request->order];

            if (! $order) {
                $orderData['cancel'] = ['error' => 'Incorrect order ID'];
            } elseif (! $order->service->cancel || $order->status !== 'pending') {
                $orderData['cancel'] = ['error' => 'You are not eligible to cancel this order.'];
            } else {
                $order->status = 'canceled';
                $order->save();

                // Refund user
                $this->orderService->processRefund($order, $order->price);

                $orderData['cancel'] = 1;
            }

            return response()->json([$orderData], 200);
        } else {
            // Bulk order cancellation
            $validator = Validator::make($request, [
                'orders' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()->first()], 422);
            }

            $orderIds = explode(',', $request['orders']);
            $response = [];

            foreach ($orderIds as $orderId) {
                $orderData = ['order' => (int) $orderId];

                $order = Order::where('id', $orderId)
                    ->where('user_id', $user->id)
                    ->with('service')
                    ->first();

                if (! $order) {
                    $orderData['cancel'] = ['error' => 'Incorrect order ID'];
                } elseif (! $order->service->cancel || $order->status !== 'pending') {
                    $orderData['cancel'] = ['error' => 'You are not eligible to cancel this order.'];
                } else {
                    $order->status = 'canceled';
                    $order->save();

                    // Refund user
                    $this->orderService->processRefund($order, $order->price);

                    $orderData['cancel'] = 1;
                }

                $response[] = $orderData;
            }

            return response()->json($response, 200);
        }
    }
}
