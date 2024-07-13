<?php

namespace App\Livewire\Forms;

use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class MemberForm extends Form
{
    /**
     * The member instance for this form.
     *
     * @var Member|null
     */
    public ?Member $member;

    /**
     * The number card of the member.
     *
     * @var string
     */
    public $number_card;

    /**
     * The full name of the member.
     *
     * @var string
     */
    public $full_name;

    /**
     * The email of the member.
     *
     * @var string
     */
    public $email;

    /**
     * The phone number of the member.
     *
     * @var string
     */
    public $phone_number;

    /**
     * The address of the member.
     *
     * @var string
     */
    public $address;

    /**
     * The image name of the member.
     *
     * @var \Livewire\Features\SupportFileUploads\TemporaryUploadedFile|string
     */
    public $image_name;

    /**
     * Get the validation rules for the form.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'image_name' => 'required|max:2048',
        ];
    }

    /**
     * Set the member instance and populate form fields.
     *
     * @param Member $member
     * @return void
     */
    public function setMember(Member $member): void
    {
        $this->member = $member;

        $this->number_card = $member->number_card;
        $this->full_name = $member->full_name;
        $this->email = $member->email;
        $this->phone_number = $member->phone_number;
        $this->address = $member->address;
        $this->image_name = $member->image_name;
    }

    /**
     * Store a new member.
     *
     * @return void
     */
    public function store(): void
    {
        $rules = $this->rules();
        $rules['email'] .= '|unique:members,email';
        $rules['phone_number'] .= '|unique:members,phone_number';

        $validatedData = $this->validate($rules);

        $this->number_card = $this->generateUniqueNumberCard();

        $fileName = $this->image_name->hashName(); 
        $this->image_name->storeAs('members', $fileName, 'public');

        Member::create(array_merge($validatedData, [
            'number_card' => $this->number_card,
            'image_name' => $fileName,
            'user_id' => Auth::user()->id,
        ]));

        $this->reset();

        session()->flash('success', 'Member successfully added.');
    }

    /**
     * Update an existing member.
     *
     * @return void
     */
    public function update(): void
    {
        $member = $this->member;

        $rules = $this->rules();
        $rules['email'] .= '|'.Rule::unique('members', 'email')->ignore($member->id);
        $rules['phone_number'] .= '|'.Rule::unique('members', 'phone_number')->ignore($member->id);

        if ($this->image_name !== $member->image_name) {
            $rules['image_name'] .= '|image|mimes:jpeg,png,jpg,gif|max:2048';
            
            $fileName = $this->image_name->hashName(); 
            $this->image_name->storeAs('members', $fileName, 'public');
        } else {
            $fileName = $member->image_name;
        }

        $validatedData = $this->validate($rules);
        $member->update(array_merge($validatedData, [
            'image_name' => $fileName,
            'user_id' => Auth::user()->id,
        ]));

        $this->reset();

        session()->flash('success', 'Member successfully updated.');
    }

    /**
     * Generate a unique number card for the member.
     *
     * @return string
     */
    protected function generateUniqueNumberCard(): string
    {
        do {
            $number_card = str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (Member::where('number_card', $number_card)->exists());

        return $number_card;
    }
}

