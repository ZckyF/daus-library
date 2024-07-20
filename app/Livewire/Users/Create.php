<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Spatie\Permission\Models\Role;

#[Title('Create User')]
class Create extends Component
{
    use WithFileUploads;
    /**
     * The form instance.
     * 
     * @var UserForm
     */
    public UserForm $form;

    /**
     * Save the new user data.
     *
     * @return void
     */
    public function save(): void
    {
        if(Gate::denies('create', User::class)) {
            abort(403);
        }
        $this->form->store();
        $this->redirectRoute('users');
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $employees= Employee::query()->orderBy('full_name','asc')->get();
        $roles = Role::query()->where('name','!=','super_admin')->orderBy('name','asc')->get();
        return view('livewire.users.create',compact('employees','roles'));
    }
}
