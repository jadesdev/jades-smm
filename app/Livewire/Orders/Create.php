<?php

namespace App\Livewire\Orders;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Services\OrderService;
use App\Traits\LivewireToast;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('user.layouts.main')]
class Create extends Component
{
    use LivewireToast;

    public Collection $categories;

    public Collection $services;

    public ?int $category_id = null;

    public ?int $service_id = null;

    public string $link = '';

    public ?int $quantity = null;

    public ?string $comments = null;

    public ?string $comments_custom_package = null;

    public ?string $usernames_custom = null;

    public array $usernames = [];

    public array $hashtags = [];

    public ?string $hashtag = null;

    public ?string $username = null;

    public ?string $media_url = null;

    public ?string $sub_username = null;

    public ?int $sub_posts = null;

    public ?int $sub_min = null;

    public ?int $sub_max = null;

    public ?int $sub_delay = null;

    public ?int $sub_expiry = null;

    public bool $is_drip_feed = false;

    public ?int $runs = null;

    public ?int $interval = null;

    public ?int $total_quantity = null;

    public ?Service $selectedService = null;

    public float $charge = 0.00;

    public float $userBalance = 0.00;

    public string $metaTitle = 'Create Order';

    protected function rules()
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'service_id' => 'required|exists:services,id',
        ];
        if (! $this->selectedService) {
            return $rules;
        }

        switch ($this->selectedService->type) {
            case 'custom_comments':
                $rules['link'] = 'required|url';
                $rules['comments'] = 'required|string|min:1';
                break;

            case 'mentions_custom_list':
                $rules['link'] = 'required|url';
                $rules['usernames_custom'] = 'required|string|min:1';
                break;

            case 'mentions_with_hashtags':
                $rules['link'] = 'required|url';
                $rules['quantity'] = $this->getQuantityRules();
                $rules['usernames'] = 'required|string';
                $rules['hashtags'] = 'required|string';
                break;

            case 'mentions_hashtag':
                $rules['link'] = 'required|url';
                $rules['quantity'] = $this->getQuantityRules();
                $rules['hashtag'] = 'required|string';
                break;

            case 'mentions_user_followers':
            case 'comment_likes':
                $rules['link'] = 'required|url';
                $rules['quantity'] = $this->getQuantityRules();
                $rules['username'] = 'required|string';
                break;

            case 'mentions_media_likers':
                $rules['link'] = 'required|url';
                $rules['quantity'] = $this->getQuantityRules();
                $rules['media_url'] = 'required|url';
                break;

            case 'package':
            case 'custom_comments_package':
                $rules['link'] = 'required|url';
                if ($this->selectedService->type === 'custom_comments_package') {
                    $rules['comments'] = 'required|string|min:1';
                }
                break;

            case 'subscriptions':
                $rules['sub_username'] = 'required|string|min:3';
                $rules['sub_posts'] = 'required|integer|min:1';
                $rules['sub_min'] = 'required|integer|min:'.$this->selectedService->min;
                $rules['sub_max'] = 'required|integer|gte:sub_min|max:'.$this->selectedService->max;
                $rules['sub_delay'] = 'required|integer|in:0,5,10,15,30,60,90';
                $rules['sub_expiry'] = 'nullable|date';
                break;

            default:
                $rules['link'] = 'required|url';
                $rules['quantity'] = $this->getQuantityRules();
                break;
        }

        if ($this->selectedService->drip_feed && $this->is_drip_feed) {
            $rules['runs'] = 'required|integer|min:2';
            $rules['interval'] = 'required|integer|min:1';
        }

        return $rules;
    }

    protected function getQuantityRules(): array
    {
        return [
            'required',
            'integer',
            'min:'.($this->selectedService->min ?? 1),
            'max:'.($this->selectedService->max ?? 1000),
        ];
    }

    protected function validationAttributes()
    {
        return [
            'category_id' => 'Category',
            'service_id' => 'Service',
            'sub_username' => 'Subscription Username',
            'sub_posts' => 'Number of Posts',
            'sub_min' => 'Min Quantity',
            'sub_max' => 'Max Quantity',
            'usernames_custom' => 'Usernames List',
            'media_url' => 'Media URL',
        ];
    }

    public function updatedCategoryId($value)
    {
        $this->reset('service_id', 'selectedService', 'quantity', 'charge', 'link');

        if ($value) {
            $this->services = Service::where('category_id', $value)->active()->get();
        } else {
            $this->services = collect();
        }
    }

    public function updatedServiceId($value)
    {
        $this->reset([
            'quantity',
            'charge',
            'link',
            'comments',
            'usernames',
            'usernames_custom',
            'hashtags',
            'hashtag',
            'username',
            'media_url',
            'sub_username',
            'sub_posts',
            'sub_min',
            'sub_max',
            'sub_delay',
            'sub_expiry',
            'is_drip_feed',
            'runs',
            'interval',
            'total_quantity',
        ]);

        if ($value) {
            $this->selectedService = Service::find($value);
            if ($this->selectedService) {
                if (! in_array($this->selectedService->type, ['package', 'custom_comments_package', 'custom_comments', 'mentions_custom_list', 'subscriptions'])) {
                    $this->quantity = $this->selectedService->min;
                }
                if ($this->selectedService->drip_feed) {
                    $this->runs = 10;
                }
                $this->calculateCharge();
            }
        } else {
            $this->selectedService = null;
        }
    }

    public function updatedQuantity()
    {
        $this->calculateCharge();
    }

    private function updateServiceDesc()
    {
        if ($this->selectedService && $this->selectedService->description == null) {
            $this->selectedService->description = '
            1. Please make sure your page is not Private.
            2. Kindly refrain from placing a second order on the same link until your initial order is completed.
            3. Please be note that there may be speed changes in service delivery during periods of high demand.
            ';
        }
    }

    /**
     * Place Order
     */
    public function placeOrder(OrderService $orderService)
    {
        $this->validate();
        $data = [
            'link' => $this->link,
            'quantity' => $this->quantity,
            'comments' => $this->comments,

            // dripfeed
            'is_drip_feed' => $this->is_drip_feed,
            'runs' => $this->runs,
            'interval' => $this->interval,

            // Mentions Fields
            'usernames' => $this->usernames,
            'usernames_custom' => $this->usernames_custom,
            'hashtags' => $this->hashtags,
            'hashtag' => $this->hashtag,
            'username' => $this->username,
            'media_url' => $this->media_url,

            // Subscription Fields
            'sub_username' => $this->sub_username,
            'sub_posts' => $this->sub_posts,
            'sub_min' => $this->sub_min,
            'sub_max' => $this->sub_max,
            'sub_delay' => $this->sub_delay,
            'sub_expiry' => $this->sub_expiry,
        ];
        try {
            $orderService->createOrder(
                Auth::user(),
                $this->selectedService,
                $data
            );

            $this->successAlert('Your order has been placed successfully!');

            return $this->redirect(route('user.orders'), navigate: true);
        } catch (InsufficientBalanceException $e) {
            $this->errorAlert($e->getMessage());
        } catch (\Exception $e) {
            $this->errorAlert('Failed to place order. Please try again.');
        }
    }

    public function updatedComments()
    {
        if ($this->selectedService && $this->selectedService->type === 'custom_comments') {
            $this->quantity = count(array_filter(explode("\n", $this->comments)));
            $this->calculateCharge();
        }
    }

    public function updatedUsernamesCustom()
    {
        if ($this->selectedService && $this->selectedService->type === 'mentions_custom_list') {
            $this->quantity = count(array_filter(explode("\n", $this->usernames_custom)));
            $this->calculateCharge();
        }
    }

    public function updatedIsDripFeed()
    {
        $this->calculateCharge();
    }

    public function updatedRuns()
    {
        $this->calculateCharge();
    }

    public function updatedSubMin()
    {
        $this->calculateCharge();
    }

    public function updatedSubMax()
    {
        $this->calculateCharge();
    }

    public function updatedSubPosts()
    {
        $this->calculateCharge();
    }

    private function calculateCharge()
    {
        if (! $this->selectedService) {
            $this->charge = 0.00;
            $this->total_quantity = 0;

            return;
        }

        $pricePerThousand = $this->selectedService->price;
        $localQuantity = 0;

        switch ($this->selectedService->type) {
            case 'package':
            case 'custom_comments_package':
                $this->charge = $pricePerThousand;
                $localQuantity = 1;
                break;

            case 'subscriptions':
                $posts = (int) $this->sub_posts;
                $max_qty = (int) $this->sub_max;
                $this->charge = $posts > 0 && $max_qty > 0 ? ($max_qty / 1000) * $pricePerThousand * $posts : 0.00;
                $localQuantity = $max_qty * $posts;
                break;

            default:
                $localQuantity = (int) $this->quantity;
                if ($this->is_drip_feed && $this->runs > 0) {
                    $localQuantity *= (int) $this->runs;
                }
                $this->charge = $localQuantity > 0 ? ($localQuantity / 1000) * $pricePerThousand : 0.00;
                break;
        }
        $this->total_quantity = $localQuantity;
    }

    public function mount()
    {
        $this->categories = Category::where('is_active', true)->has('activeServices')->orderBy('name')->get();
        $this->services = collect();
        $this->userBalance = Auth::user()->balance ?? 0.00;

        $service_id = request()->get('service_id');
        if ($service_id) {
            $service = Service::find($service_id);
            if ($service) {
                $this->category_id = $service->category_id;
                $this->updatedCategoryId($service->category_id);
                $this->updatedServiceId($service_id);
                $this->service_id = (int) $service_id;

                $this->successAlert('Service selected successfully!');
            }
        }
    }

    public function render()
    {
        $this->updateServiceDesc();

        return view('livewire.orders.create');
    }
}
