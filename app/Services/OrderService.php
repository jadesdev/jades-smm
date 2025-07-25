<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{

    public function createOrder(User $user, Service $service, array $data): Order
    {
        $totalQuantity = $this->calculateTotalQuantity($service, $data);
        $charge = $this->calculateCharge($service, $totalQuantity, $data);
        $apiPrice = $this->calculateApiPrice($service, $totalQuantity, $data);

        if ($user->balance < $charge) {
            throw new InsufficientBalanceException('You do not have enough funds to place this order.');
        }

        DB::beginTransaction();
        try {
            $orderData = [
                'user_id' => $user->id,
                'category_id' => $service->category_id,
                'service_id' => $service->id,
                'api_provider_id' => $service->api_provider_id,
                'api_service_id' => $service->api_service_id,
                'type' => $service->api_provider_id ? 'api' : 'direct',
                'service_type' => $service->type,
                'link' => $data['link'] ?? null,
                'quantity' => $totalQuantity,
                'remains' => $totalQuantity,
                'price' => $charge,
                'api_price' => $apiPrice,
                'profit' => $charge - $apiPrice,
                'status' => 'pending',

                // Service-specific text/json fields
                'comments' => in_array($service->type, ['custom_comments', 'custom_comments_package']) ? json_encode(array_filter(explode("\n", $data['comments'] ?? ''))) : null,
                'usernames' => $service->type === 'mentions_custom_list' ? json_encode(array_filter(explode("\n", $data['usernames_custom'] ?? ''))) : $this->ensureStringOrNull($data['usernames'] ?? null),
                'username' => in_array($service->type, ['mentions_user_followers', 'comment_likes']) ? $data['username'] : null,
                'hashtags' => $service->type === 'mentions_with_hashtags' ? $this->ensureStringOrNull($data['hashtags'] ?? null) : null,
                'hashtag' => $service->type === 'mentions_hashtag' ? $data['hashtag'] : null,
                'media' => $service->type === 'mentions_media_likers' ? $data['media_url'] : null,

                // Subscription fields
                'sub_status' => $service->type === 'subscriptions' ? 'active' : null,
                'sub_posts' => $data['sub_posts'] ?? null,
                'sub_min' => $data['sub_min'] ?? null,
                'sub_max' => $data['sub_max'] ?? null,
                'sub_delay' => $data['sub_delay'] ?? null,
                'sub_expiry' => $data['sub_expiry'] ?? null,

                // Drip-feed fields
                'is_drip_feed' => $data['is_drip_feed'] ?? false,
                'runs' => $data['is_drip_feed'] ? ($data['runs'] ?? 0) : 0,
                'interval' => $data['is_drip_feed'] ? ($data['interval'] ?? 0) : 0,
                'dripfeed_quantity' => $data['is_drip_feed'] ? ($data['quantity'] ?? 0) : 0,
            ];
            $order = Order::create($orderData);
            $user->decrement('balance', $charge);

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed in OrderService: ' . $e->getMessage());
            throw new \Exception('Failed to create the order due to a server error.');
        }
    }

    private function ensureStringOrNull($value)
    {
        if (is_null($value)) {
            return null;
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }
    private function calculateTotalQuantity(Service $service, array $data): int
    {
        $quantity = 0;

        switch ($service->type) {
            case 'custom_comments':
                $quantity = count(array_filter(explode("\n", $data['comments'] ?? '')));
                break;
            case 'mentions_custom_list':
                $quantity = count(array_filter(explode("\n", $data['usernames_custom'] ?? '')));
                break;
            case 'package':
            case 'custom_comments_package':
                $quantity = 1;
                break;
            default:
                $quantity = (int)($data['quantity'] ?? 0);
                break;
        }

        // Apply drip-feed multiplication if enabled.
        if ($data['is_drip_feed'] ?? false) {
            $quantity *= (int)($data['runs'] ?? 1);
        }

        return $quantity;
    }

    /**
     * Calculates the final charge to the user.
     */
    private function calculateCharge(Service $service, int $totalQuantity, array $data): float
    {
        switch ($service->type) {
            case 'package':
            case 'custom_comments_package':
                return (float)$service->price;

            case 'subscriptions':
                $posts = (int)($data['sub_posts'] ?? 0);
                $maxQty = (int)($data['sub_max'] ?? 0);
                return ($maxQty / 1000) * (float)$service->price * $posts;

            default:
                return ($totalQuantity / 1000) * (float)$service->price;
        }
    }

    /**
     * Calculates the cost from the API provider (for profit calculation).
     */
    private function calculateApiPrice(Service $service, int $totalQuantity, array $data): float
    {
        if (!$service->api_provider_id || !$service->original_price) {
            return 0.00;
        }

        switch ($service->type) {
            case 'package':
            case 'custom_comments_package':
                return (float)$service->original_price;

            case 'subscriptions':
                $posts = (int)($data['sub_posts'] ?? 0);
                $maxQty = (int)($data['sub_max'] ?? 0);
                return ($maxQty / 1000) * (float)$service->original_price * $posts;

            default:
                return ($totalQuantity / 1000) * (float)$service->original_price;
        }
    }
}
