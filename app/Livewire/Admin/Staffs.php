<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use App\Traits\LivewireToast;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;

#[Layout('admin.layouts.main')]
class Staffs extends Component
{
    use LivewireToast;
    public $view = 'list';
    public $staffs = [];
    public $staffId;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $type;
    public $is_active;
    public $deletingStaff;


    // meta
    public string $metaTitle = "Staffs";

    public function add()
    {
        $this->view = 'showForm';
        $this->resetForm();
    }

    public function edit($id)
    {
        $staff = Admin::findOrFail($id);
        $this->staffId = $id;
        $this->name = $staff->name;
        $this->email = $staff->email;
        $this->phone = $staff->phone;
        $this->type = $staff->type;
        $this->is_active = $staff->is_active;
        $this->view = 'showForm';
    }

    protected function resetForm()
    {
        $this->reset([
            'staffId',
            'name',
            'email',
            'phone',
            'password',
            'type',
            'is_active',
        ]);
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($this->staffId),
            ],
            'phone' => 'nullable|string|max:255',
            'password' => $this->staffId ? 'nullable|string|min:8' : 'required|string|min:8',
            'type' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        // Handle password only if filled
        if (!empty($this->password)) {
            $validated['password'] = bcrypt($this->password);
        } else {
            unset($validated['password']); // avoid null overwrite
        }

        if ($this->staffId) {
            $staff = Admin::findOrFail($this->staffId);
            $staff->update($validated);
            $this->successAlert('Staff updated successfully');
        } else {
            Admin::create($validated);
            $this->successAlert('Staff created successfully');
        }

        $this->backToList();
    }

    public function backToList()
    {
        $this->view = 'list';
        $this->resetForm();
    }
    public function delete($id)
    {
        $this->deletingStaff = Admin::findOrFail($id);
        $this->dispatch('open-modal', name: 'delete-modal');
    }

    public function closeDeleteModal()
    {
        $this->deletingStaff = null;
        $this->dispatch('close-modal', name: 'delete-modal');
    }

    public function deleteStaff()
    {
        $staff = Admin::findOrFail($this->deletingStaff->id);
        if ($staff->type == 'super') {
            $this->errorAlert('You cannot delete super admin');
            return;
        }
        // $staff->delete();
        $this->closeDeleteModal();
        $this->successAlert('Staff deleted successfully');
    }

    public function render()
    {
        $this->staffs = Admin::all();
        return view('livewire.admin.staffs');
    }
}
