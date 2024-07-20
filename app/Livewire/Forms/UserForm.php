<?php

namespace App\Livewire\Forms;

use App\Models\User;
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
        return [
            'username' => 'required|string|max:255|min:5|alpha_dash',
            'password' => 'string|max:255|min:8',
            'employee_id' => 'required|integer',
            'avatar_name' => 'required|max:2048',
            'role_id' => 'required|integer',
        ];
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
            // 'employee_id.integer' => 'The employee must be an integer.',
            'avatar_name.required' => 'The avatar is required.',
            'avatar_name.max' => 'The avatar is required.',
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

    }
}
