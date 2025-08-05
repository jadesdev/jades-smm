<?php

namespace App\Livewire\Admin;

use App\Models\Newsletter;
use App\Traits\LivewireToast;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Stevebauman\Purify\Facades\Purify;

#[Layout('admin.layouts.main')]
class BulkEmail extends Component
{
    use LivewireToast;
    use WithPagination;

    public $view = 'list';

    // meta
    public string $metaTitle = 'BulkEmail';

    public $deletingItem;

    public $newsletterId;

    public $user_emails = false;

    public $other_emails;

    public $subject;

    public $content;

    public $date;

    public $status = 2;

    public function backToList()
    {
        $this->view = 'list';
        $this->resetForm();
    }

    public function add()
    {
        $this->view = 'showForm';
        $this->resetForm();
    }

    public function edit($id)
    {
        $newsletter = Newsletter::findOrFail($id);

        $this->newsletterId = $id;
        $this->user_emails = $newsletter->user_emails;
        $this->other_emails = $newsletter->other_emails;
        $this->subject = $newsletter->subject;
        $this->content = $newsletter->content;
        $this->date = $newsletter->date?->format('Y-m-d');
        $this->status = $newsletter->status;

        $this->view = 'showForm';
    }

    protected function resetForm()
    {
        $this->reset([
            'newsletterId',
            'user_emails',
            'other_emails',
            'subject',
            'content',
            'date',
            'status',
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'user_emails' => 'required|boolean',
            'other_emails' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $emails = array_map('trim', explode(',', $value));
                        foreach ($emails as $email) {
                            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $fail("The email '{$email}' is not valid.");
                            }
                        }
                    }
                },
            ],
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|in:1,2',
        ]);

        $cleanHtml = Purify::clean($this->content);
        $validated['content'] = $cleanHtml;

        if ($this->newsletterId) {
            $newsletter = Newsletter::findOrFail($this->newsletterId);
            $newsletter->update($validated);
            $this->successAlert('Newsletter updated successfully');
        } else {
            Newsletter::create($validated);
            $this->successAlert('Newsletter created successfully');
        }

        $this->backToList();
    }

    public function delete($id)
    {
        $this->deletingItem = Newsletter::findOrFail($id);
        $this->dispatch('open-modal', name: 'delete-modal');
    }

    public function closeDeleteModal()
    {
        $this->deletingItem = null;
        $this->dispatch('close-modal', name: 'delete-modal');
    }

    public function deleteNewsletter()
    {
        $this->deletingItem->delete();
        $this->closeDeleteModal();
        $this->successAlert('Newsletter deleted successfully');
    }

    public function mount($id = null): void
    {
        if ($id) {
            $this->view = 'showForm';
            $this->edit($id);
        } else {
            $this->view = 'list';
        }
    }

    public function render()
    {
        $newsletters = Newsletter::paginate(10);

        return view('livewire.admin.bulk-email', compact('newsletters'));
    }
}
