<?php

namespace App\Livewire\Forms;

use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MemberForm extends Form
{
    public $number_card;
    public $full_name;
    public $email;
    public $phone_number;
    public $address;
    public $image_name;

    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'image_name' => 'required|max:2048',
        ];
    }


    public function store()
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

        $this->resetForm();

        session()->flash('success', 'Member successfully added.');
    }

    public function update($memberId)
    {
        $member = Member::findOrFail($memberId);

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
            'number_card' => $this->number_card,
            'image_name' => $fileName,
            'user_id' => Auth::user()->id,
        ]));

        $this->resetForm();

        session()->flash('success', 'Member successfully updated.');
    }

    

    protected function generateUniqueNumberCard()
    {
        do {
            $number_card = str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (Member::where('number_card', $number_card)->exists());

        return $number_card;
    }

    protected function resetForm() 
    {
         $this->reset('number_card', 'full_name', 'email', 'phone_number', 'address', 'image_name');
    }
}
