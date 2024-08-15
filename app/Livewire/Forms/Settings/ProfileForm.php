<?php

namespace App\Livewire\Forms\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProfileForm extends Form
{
    /**
     * The profile(user) instance for the form.
     * 
     * @var User
     */
    public User $profile;

    /**
     * The avatar_name of the profile.
     * 
     * @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|string
     */
    public $avatar_name;
    /**
     * The username of the profile.
     * 
     * @var string
     */
    public $username;
    /**
     * The full_name of the profile.
     * 
     * @var string
     */
    public $full_name;
    /**
     * The email of the profile.
     * 
     * @var string
     */
    public $email;
    /**
     * The phone_number of the profile.
     * 
     * @var string
     */
    public $phone_number;
    /**
     * The address of the profile.
     * 
     * @var string
     */
    public $address;

    /**
     * Get the validation rules that apply to the request.
     * 
     * @return array
     */
    public function rules(): array
    {
        return[
            'avatar_name' => 'required|max:2048',
            'username' => 'required|max:255|min:3|alpha_dash|'.Rule::unique('users', 'username')->ignore($this->profile->id),
        ];
    }
    /**
     * Get the validation messages that apply to the request.
     * 
     * @return array
     */
    public function messages(): array
    {
        return[
            'avatar_name.required' => 'The avatar is required.',
            'avatar_name.max' => 'The avatar is required.',
            'avatar_name.image' => 'The avatar must be an image.',
            'avatar_name.mimes' => 'The avatar must be a file of type: jpeg, png, jpg, gif.',
        ];
    }
    /**
     * Set the profile for the form.
     * 
     * @param User $profile
     * @return void
     */
    public function setProfile(User $profile): void
    {
        $this->profile = $profile;
        $this->avatar_name = $profile->avatar_name;
        $this->username = $profile->username;
        $this->full_name = $profile->employee->full_name;
        $this->email = $profile->employee->email;
        $this->phone_number = $profile->employee->phone_number;
        $this->address = $profile->employee->address;
    }
    /**
     * Update the profile.
     * 
     * @return void
     */
    public function update(): void
    {
        $profile = $this->profile;
        $rules = $this->rules();
        if($this->avatar_name !== $profile->avatar_name) {
            $rules['avatar_name'] .= '|image|mimes:jpeg,png,jpg,gif';

            $filename = $this->avatar_name->hashName();
            $this->avatar_name->storeAs('avatars', $filename, 'public');
            if($profile->employee->avatar_name && $profile->employee->avatar_name !== 'default.jpg') {
                Storage::disk('public')->delete('avatars/' . $profile->avatar_name);
            }
        } else {
            $filename = $profile->avatar_name;
        }
        $validatedData = $this->validate($rules);
        $profile->update(array_merge($validatedData, [
            'avatar_name' => $filename,
        ]));

        session()->flash('success', 'Profile successfully updated.');
    }
}
