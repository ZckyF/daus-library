<?php

namespace App\Livewire\Forms;

use App\Models\Fine;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class FineForm extends Form
{
    /**
     * The current fine for the form.
     * 
     * @var Fine|null
     */
    public ?Fine $fine = null;
    /**
     * The selected member for the current fine.
     * 
     * @var string
     */
    public $selectedMember;
    /**
     * The member id for the current fine.
     * 
     * @var int
     */
    public $member_id;
    /**
     * The non member name for the current fine.
     * 
     * @var string
     */
    public $non_member_name;
    /**
     * The amount for the current fine.
     * 
     * @var int
     */
    public $amount;
    /**
     * The amount paid for the current fine.
     * 
     * @var int
     */
    public $amount_paid;
    /**
     * The change amount for the current fine.
     * 
     * @var int
     */
    public $change_amount;
    /**
     * The reason for the current fine.
     * 
     * @var string
     */
    public $reason;
    /**
     * The charged at for the current fine.
     * 
     * @var string
     */
    public $charged_at;
    /**
     * The is paid for the current fine.
     * 
     * @var bool
     */
    public $is_paid = 0;

    public function rules(): array
    {
        return [
            'selectedMember' => 'nullable|string|max:255|required_without:non_member_name',
            'non_member_name' => 'nullable|string|max:255|required_without:selectedMember',
            'amount' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'change_amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
            'charged_at' => 'nullable|date',
            'is_paid' => 'required|boolean',

        ];
    }
    public function store(): void
    {
        $validatedData = $this->validate();
        $fineNumber = time() . rand(1000, 9999);

        Fine::create(array_merge($validatedData, [
            'fine_number' => $fineNumber,
            'member_id' => $this->member_id,
            'user_id' => Auth::user()->id
        ]));
        
        session()->flash('success', 'Fine successfully created');

        $this->reset();
    }

    public function update(): void
    {

    }
}
