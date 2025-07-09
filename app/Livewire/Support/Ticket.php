<?php

namespace App\Livewire\Support;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\LivewireToast;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Stevebauman\Purify\Facades\Purify;

#[Layout('user.layouts.main')]
class Ticket extends Component
{
    use LivewireToast, WithFileUploads;

    public Collection $ticketmessages;

    public string $metaTitle;

    public string $metaDescription;

    public string $metaKeywords;

    public string $metaImage;

    public SupportTicket $ticket;

    public string $message = '';

    public $image;

    public function mount($code)
    {
        $ticket = SupportTicket::where('code', $code)->first();
        if (! $ticket) {
            $this->errorAlert('Ticket not found');
            $this->redirect(route('user.support'), navigate: true);
        }
        // \Log::info($ticket);
        if ($ticket->user_id != Auth::id()) {
            $this->errorAlert('Access denied');
            $this->redirect(route('user.support'), navigate: true);
        }
        // set meta
        $this->metaTitle = "Ticket - #{$ticket->code}";
        $this->metaDescription = "View and manage support ticket #{$ticket->code}";
        $this->metaKeywords = 'support, ticket, help';
        $this->metaImage = '';

        // set ticket
        $this->ticket = $ticket;
        $this->loadMessages();
    }

    public function closeTicket()
    {
        if ($this->ticket->status === SupportTicket::STATUS_OPEN) {
            $this->ticket->update([
                'status' => SupportTicket::STATUS_CLOSED,
                'updated_at' => now(),
            ]);

            $this->successAlert('Ticket closed successfully');
            $this->dispatch('ticket-updated');
        }
    }

    public function openTicket()
    {
        if ($this->ticket->status === SupportTicket::STATUS_CLOSED) {
            $this->ticket->update([
                'status' => SupportTicket::STATUS_OPEN,
                'updated_at' => now(),
            ]);

            $this->successAlert('Ticket opened successfully');
            $this->dispatch('ticket-updated');
        }
    }

    public function getIsClosedProperty()
    {
        return $this->ticket->status === SupportTicket::STATUS_CLOSED;
    }

    public function loadMessages()
    {
        $this->ticketmessages = $this->ticket->messages()->orderBy('created_at', 'asc')->get();
    }

    public function rules()
    {
        return [
            'message' => 'required|min:1|max:1000',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function sendMessage()
    {
        $this->validate();

        if ($this->ticket->status === SupportTicket::STATUS_CLOSED) {
            $this->errorAlert('Cannot send message to a closed ticket');

            return;
        }

        $imagePath = null;
        if ($this->image) {
            try {
                $storedPath = $this->image->store('support', 'uploads');
                $imagePath = 'support/'.basename($storedPath);
            } catch (\Exception $e) {
                $this->errorAlert('Failed to upload image');

                return;
            }
        }

        $this->ticket->messages()->create([
            'user_id' => Auth::id(),
            'message' => Purify::clean($this->message),
            'type' => $this->image ? SupportMessage::TYPE_IMAGE : SupportMessage::TYPE_TEXT,
            'image' => $imagePath,
            'is_admin' => false,
        ]);

        $this->ticket->touch();
        $this->loadMessages();

        $this->reset(['message', 'image']);

        $this->successAlert('Message sent successfully');

        $this->dispatch('message-sent');
    }

    public function render()
    {
        return view('livewire.support.ticket');
    }
}
