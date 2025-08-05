<?php

namespace App\Livewire\Admin\Support;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Traits\LivewireToast;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Stevebauman\Purify\Facades\Purify;

#[Layout('admin.layouts.main')]
class Message extends Component
{
    use LivewireToast, WithFileUploads;

    // meta
    public string $metaTitle;

    // Ticket Data
    public SupportTicket $ticket;

    public $ticketmessages;

    // Form Inputs
    public string $message = '';

    public $image;

    public function mount($id)
    {
        $this->ticket = SupportTicket::findOrFail($id);
        $this->loadMessages();

        $this->metaTitle = "Support Ticket - #{$this->ticket->code}";
    }

    public function rules()
    {
        return [
            'message' => 'required|min:1|max:1000',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function getIsClosedProperty()
    {
        return $this->ticket->status === SupportTicket::STATUS_CLOSED;
    }

    public function getIsResolvedProperty()
    {
        return $this->ticket->status === SupportTicket::STATUS_RESOLVED;
    }

    public function loadMessages()
    {
        $this->ticketmessages = $this->ticket->messages()->orderBy('created_at', 'asc')->get();
    }

    public function markAsResolved()
    {
        if ($this->ticket->status !== SupportTicket::STATUS_RESOLVED) {
            $this->ticket->update([
                'status' => SupportTicket::STATUS_RESOLVED,
                'updated_at' => now(),
            ]);

            $this->successAlert('Ticket marked as resolved');
            $this->dispatch('ticket-updated');
        }
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
        if ($this->ticket->status === SupportTicket::STATUS_CLOSED || $this->ticket->status === SupportTicket::STATUS_RESOLVED) {
            $this->ticket->update([
                'status' => SupportTicket::STATUS_OPEN,
                'updated_at' => now(),
            ]);

            $this->successAlert('Ticket opened successfully');
            $this->dispatch('ticket-updated');
        }
    }

    public function assignToMe()
    {
        $this->ticket->update([
            'assigned_to' => Auth::guard('admin')->user()->id,
            'updated_at' => now(),
        ]);

        $this->successAlert('Ticket assigned to you');
        $this->dispatch('ticket-updated');
    }

    public function unassignTicket()
    {
        $this->ticket->update([
            'assigned_to' => null,
            'updated_at' => now(),
        ]);

        $this->successAlert('Ticket unassigned');
        $this->dispatch('ticket-updated');
    }

    public function sendMessage()
    {
        $this->validate();

        if ($this->ticket->status === 'closed') {
            $this->errorAlert('Cannot send a message to a closed ticket.');

            return;
        }

        $imagePath = null;
        if ($this->image) {
            try {
                $storedPath = $this->image->store('support', 'uploads');
                $imagePath = 'support/'.basename($storedPath);
            } catch (\Exception $e) {
                $this->errorAlert('Failed to upload image. Please try again.');

                return;
            }
        }

        $this->ticket->messages()->create([
            'user_id' => null,
            'message' => Purify::clean($this->message),
            'type' => $this->image ? SupportMessage::TYPE_IMAGE : SupportMessage::TYPE_TEXT,
            'image' => $imagePath,
            'is_admin' => true,
        ]);

        $this->ticket->touch();

        $this->loadMessages();

        $this->reset(['message', 'image']);

        // send notification to user
        sendNotification('SUPPORT_TICKET_REPLY_USER', $this->ticket->user, [
            'ticket_id' => $this->ticket->code,
            'ticket_subject' => $this->ticket->subject,
            'ticket_link' => route('user.support.view', $this->ticket->code),
            'reply_preview' => textTrim($this->message, 50),
            'name' => $this->ticket->user->name,
        ], [
            'link' => route('user.support.view', $this->ticket->code),
            'link_text' => 'View Ticket',
        ]);
        $this->dispatch('message-sent');
    }

    public function render()
    {
        return view('livewire.admin.support.message');
    }
}
