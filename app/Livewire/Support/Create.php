<?php

namespace App\Livewire\Support;

use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('user.layouts.main')]
class Create extends Component
{
    use LivewireToast;

    public array $subjectOptions = [
        'Order Inquiry' => 'Order Inquiry',
        'Payment Notification' => 'Payment Notification',
        'Other' => 'Other',
    ];

    public array $requestOptions = [
        '' => 'Please Select',
        'I think there is an error in my order, can you check it?' =>
        'I think there is an error in my order, can you check it?',
        'My order has not started yet, can I get information?' =>
        'My order has not started yet, can I get information?',
        'Why was my order canceled?' => 'Why was my order canceled?',
        'It was marked as completed but the task is not done.' =>
        'It was marked as completed but the task is not done.',
        'If possible, can you cancel it?' => 'If possible, can you cancel it?',
        'Other' => 'Other',
    ];

    public array $paymentOptions = [
        'Bank Transfer' => 'Bank Transfer',
        'Card Payment' => 'Card Payment',
        'Others' => 'Others',
    ];
    // meta
    public string $metaTitle;

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public function mount()
    {
        // set meta
        $this->metaTitle = "Create Support Ticket";
    }

    public function render()
    {
        return view('livewire.support.create');
    }
}
