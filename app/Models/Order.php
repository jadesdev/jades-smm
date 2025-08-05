<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'user_id',
        'category_id',
        'service_id',
        'parent_order_id',
        'api_service_id',
        'api_provider_id',
        'api_order_id',
        'api_refill_id',
        'type',
        'service_type',
        'link',
        'quantity',
        'start_counter',
        'remains',
        'price',
        'api_price',
        'profit',
        'status',
        'comments',
        'usernames',
        'username',
        'hashtags',
        'hashtag',
        'media',
        'sub_status',
        'sub_posts',
        'sub_min',
        'sub_max',
        'sub_delay',
        'sub_expiry',
        'sub_response_orders',
        'sub_response_posts',
        'is_drip_feed',
        'runs',
        'interval',
        'dripfeed_quantity',
        'refill_status',
        'refilled_at',
        'note',
        'error',
        'error_message',
        'response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_drip_feed' => 'boolean',
        'error' => 'boolean',
        'sub_response_orders' => 'array',
        'sub_response_posts' => 'array',
        'response' => 'array',
        'refilled_at' => 'date',
        'sub_expiry' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function provider()
    {
        return $this->belongsTo(ApiProvider::class, 'api_provider_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $params = ['category:name', 'service:name', 'user:username', 'user:name', 'user:username', 'user:email', 'service:description'];
            $query->where(function ($q) use ($params, $search) {
                foreach ($params as $key => $param) {
                    $relationData = explode(':', $param);
                    if (@$relationData[1]) {
                        $q = $this->relationSearch($q, $relationData[0], $relationData[1], $search);
                    } else {
                        $column = $param;
                        $q->orWhere($column, 'LIKE', $search);
                    }
                }
            })
                ->orwhere('id', 'like', "%$search%")
                ->orwhere('link', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%")
                ->orWhere('quantity', 'like', "%$search%")
                ->orWhere('response', 'like', "%$search%")
                ->orWhere('price', 'like', "%$search%");
        });
    }

    private function relationSearch($query, $relation, $columns, $search)
    {
        foreach (explode(',', $columns) as $column) {
            $query->orWhereHas($relation, function ($q) use ($column, $search) {
                $q->where($column, 'like', "%$search%");
            });
        }

        return $query;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now());
    }
}
