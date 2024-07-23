<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Spatie\Permission\Models\Role;

class UserForm extends Form
{
    /**
     * Optional existing User model instance.
     * 
     * @var User|null
     */
    public ?User $user = null;

    /**
     * The username for the form
     * 
     * @var string
     */
    public $username;
    /**
     * The password for the form
     * 
     * @var string
     */
    public $password;
    /**
     * The password confirmation for the form
     * 
     * @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|string
     */
    public $avatar_name;
    /**
     * The employee_id for the form
     * 
     * @var int
     */
    public $employee_id;
    /**
     * The role_id for the form
     * 
     * @var int
     */
    public $role_id;

    /**
     * The validation rules
     * 
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'username' => 'required|string|max:255|min:5|alpha_dash',
            'password' => 'string|max:255|min:8',
            'employee_id' => 'required|integer',
            'avatar_name' => 'required|max:2048',
            'role_id' => 'required|integer'
        ];
        if (Auth::user() && !Auth::user()->hasRole('super_admin')) {
            $rules['role_id'] .= '|not_in:' . Role::whereIn('name', ['admin','super_admin'])->value('id');
        }
        return $rules;
    }
    /**
     * The validation messages
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'The employee is required.',
            'role_id.required' => 'The role is required.',
            'role_id.not_in' => 'The role is not allowed.',

            // 'employee_id.integer' => 'The employee must be an integer.',
            'avatar_name.required' => 'The avatar is required.',
            'avatar_name.max' => 'The avatar is required.',
            'avatar_name.image' => 'The avatar must be an image.',
            'avatar_name.mimes' => 'The avatar must be a file of type: jpeg, png, jpg, gif.',
        ];
    }
    /**
     * Set the user instance and populate form fields.
     *
     * @param User $user
     * @return void
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
        $this->username = $user->username;
        $this->avatar_name = $user->avatar_name;
        $this->employee_id = $user->employee_id;
        $this->role_id = $user->roles->pluck('id')->first();
    }

    public function store(): void
    {
        $rules = $this->rules();
        $rules['username'] .= '|unique:users,username';
        $rules['password'] .= '|required';
        
        $validatedData = $this->validate($rules);
        $filename = $this->avatar_name->hashName();
        $this->avatar_name->storeAs('avatars', $filename, 'public');

        $user = User::create(array_merge($validatedData, [
            'avatar_name' => $filename,
        ]));
        
        $role = Role::findOrFail($this->role_id);
        $user->assignRole($role);
        $this->reset();

        session()->flash('success', 'User created successfully.');
    }

    public function update() 
    {
        $user = $this->user;
        $rules = $this->rules();
        $rules['username'] .= '|'. Rule::unique('users', 'username')->ignore($this->user);
        $rules['password'] .= '|nullable';

        if ($this->avatar_name !== $user->avatar_name) {
            $rules['avatar_name'] .= '|image|mimes:jpeg,png,jpg,gif|max:2048';
            
            $fileName = $this->avatar_name->hashName(); 
            $this->avatar_name->storeAs('avatars', $fileName, 'public');
            if ($user->avatar_name && $user->avatar_name !== 'default.jpg') {
                Storage::disk('public')->delete('avatars/' . $user->avatar_name);
            }
        } else {
            $fileName = $user->avatar_name;
        }

        $validatedData = $this->validate($rules);
        $validatedData['password'] = empty($validatedData['password']) ? $user->password : $validatedData['password'];
        $user->update(array_merge($validatedData, [
            'avatar_name' => $fileName,
        ]));
        $role = Role::findOrFail($this->role_id);
        $user->assignRole($role);
        $this->reset();
        session()->flash('success', 'User updated successfully.');
    }
}
