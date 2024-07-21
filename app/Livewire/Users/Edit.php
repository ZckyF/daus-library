<?php

namespace App\Livewire\Users;

use App\Livewire\Forms\UserForm;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Spatie\Permission\Models\Role;

#[Title('Edit User')]
class Edit extends Component
{

    use WithFileUploads;
    /**
     * The form instance.
     * 
     * @var UserForm
     */
    public UserForm $form;

    /**
     * Mount the component with the given member's username.
     *
     * @param string $username
     * @return void
     */
    public function mount(string $username): void
    {
        $user = User::where('username', $username)->firstOrFail();

        if (!$user) {
            abort(404);
        }
        if(Gate::denies('update', $user)) {
            abort(403);
        }

        $this->form->setUser($user);
    }

        /**
     * Save the user data.
     *
     * @return void
     */
    public function save(): void
    {
        if(Gate::denies('update', $this->form->user)) {
            abort(403);
        }
        $this->form->update();
        $this->redirectRoute('users');
    }

    /**
     * Delete the user and its cover image.
     *
     * @return void
     */
    public function delete(): void
    {
        if(Gate::denies('delete', $this->form->user)) {
            abort(403);
        }
        $user = $this->form->user;
    
        $avatar = $user->avatar_name;
    
        $user->delete();
    
        if ($avatar && $avatar !== 'default.jpg') {
            Storage::disk('public')->delete('avatars/' . $avatar);
        }
    
        session()->flash('success', 'User deleted successfully');
            
        $this->redirectRoute('users');
    }

    public function render()
    {
        $roles= Role::query()->where('name','!=','super_admin')->orderBy('name','asc')->get();
        $employees= Employee::query()->orderBy('full_name','asc')->get();
        $isEditPage = true;
        return view('livewire.users.edit',compact('roles','employees','isEditPage'));
    }
}
