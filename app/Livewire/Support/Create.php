<?php

namespace App\Livewire\Support;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\LivewireToast;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Stevebauman\Purify\Facades\Purify;

#[Layout('user.layouts.main')]
class Create extends Component
{
    use LivewireToast, WithFileUploads;

    public array $subjectOptions = [
        'Order Inquiry' => 'Order Inquiry',
        'Payment Notification' => 'Payment Notification',
        'Rent Panel' => 'Rent Panel',
        'Other' => 'Other',
    ];

    public array $requestOptions = [
        '' => 'Please Select',
        'I think there is an error in my order, can you check it?' => 'I think there is an error in my order, can you check it?',
        'My order has not started yet, can I get information?' => 'My order has not started yet, can I get information?',
        'Why was my order canceled?' => 'Why was my order canceled?',
        'It was marked as completed but the task is not done.' => 'It was marked as completed but the task is not done.',
        'If possible, can you cancel it?' => 'If possible, can you cancel it?',
        'Order Status' => 'Order Status',
        'Order Modification' => 'Order Modification',
        'Order Refund' => 'Order Refund',
        'Order Issue' => 'Order Issue',
        'Other' => 'Other',
    ];

    public array $paymentOptions = [
        'Bank Transfer' => 'Bank Transfer',
        'Card Payment' => 'Card Payment',
        'PayPal' => 'PayPal',
        'Cryptocurrency' => 'Cryptocurrency',
        'Others' => 'Others',
    ];

    // Form fields
    public $tickets;
    public $subject = 'Order Inquiry';
    public $orderid = '';
    public $want = '';
    public $payment = '';
    public $transactionId = '';
    public $addamount = '';
    public $message = '';
    public $image;

    // Meta
    public string $metaTitle = "Create Support Ticket";
    public string $metaDescription = '';
    public string $metaKeywords = '';
    public string $metaImage = '';

    // Validation rules
    protected function rules()
    {
        $rules = [
            'subject' => 'required|string',
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:3048', // 3MB max
        ];

        if ($this->isOrderInquiry()) {
            $rules['orderid'] = 'required|string';
            $rules['want'] = 'required|string';
        }

        if ($this->isPaymentNotification()) {
            $rules['payment'] = 'required|string';
            $rules['transactionId'] = 'required|string';
            $rules['addamount'] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function removeAttachment($index)
    {
        if (isset($this->attachments[$index])) {
            unset($this->attachments[$index]);
        }
    }
    public function isOrderInquiry()
    {
        return in_array($this->subject, ['Order Inquiry']);
    }

    public function isPaymentNotification()
    {
        return in_array($this->subject, ['Payment Notification']);
    }

    public function isRentPanel()
    {
        return in_array($this->subject, ['Rent Panel']);
    }

    public function isOther()
    {
        return in_array($this->subject, ['Other']);
    }

    private function buildConcatenatedMessage()
    {
        $concatenatedMessage = '';

        if ($this->isOrderInquiry()) {
            $concatenatedMessage = "Order number: " . $this->orderid . "\n";
            $concatenatedMessage .= "Request: " . $this->want . "\n";
            $concatenatedMessage .= $this->message;
        } elseif ($this->isPaymentNotification()) {
            $concatenatedMessage = "Payment Method: " . $this->payment . "\n";
            $concatenatedMessage .= "Sender Name: " . $this->transactionId . "\n";
            $concatenatedMessage .= "Amount: " . $this->addamount . "\n";
            $concatenatedMessage .= $this->message;
        } else {
            $concatenatedMessage = $this->message;
        }

        return $concatenatedMessage;
    }

    function loadTickets()
    {
        $this->tickets = SupportTicket::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->with(['latestMessage', 'lastMessage'])->get();
    }

    public function submit()
    {
        $this->validate();

        try {
            $user = Auth::user();
            // Create the ticket
            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'code' => SupportTicket::generateCode(),
                'subject' => $this->subject,
                'status' => SupportTicket::STATUS_OPEN,
            ]);

            $imagePath = null;
            if ($this->image) {
                $imagePath = $this->image->store('support', 'uploads');
            }

            $messageContent = $this->buildConcatenatedMessage();

            $ticket->messages()->create([
                'user_id' => $user->id,
                'message' => Purify::clean($messageContent),
                'type' => $this->image ? SupportMessage::TYPE_IMAGE : SupportMessage::TYPE_TEXT,
                'image' => $imagePath ? 'support/' . basename($imagePath) : null,
                'is_admin' => false,
            ]);

            $this->reset(['orderid', 'want', 'payment', 'transactionId', 'addamount', 'message', 'image']);
            $this->subject = 'Order Inquiry';

            $this->successAlert('Support ticket created successfully! We will respond within 24 hours.');
            $this->redirect(route('user.support.view', $ticket->id), navigate: true);
        } catch (\Exception $e) {
            \Log::error('Support ticket creation failed: ' . $e->getMessage());
            $this->errorAlert('Failed to create support ticket. Please try again.');
        }
    }

    public function updatedSubject()
    {
        $this->reset(['orderid', 'want', 'payment', 'transactionId', 'addamount']);
    }

    public function render()
    {
        $this->loadTickets();
        return view('livewire.support.create');
    }
}
