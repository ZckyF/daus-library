<?php

namespace App\Livewire\Forms;

use App\Models\Fine;
use Carbon\Carbon;
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
     * The fine number for the current fine.
     * 
     * @var string
     */
    public $fine_number;
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
    public function setFine(Fine $fine): void
    {
        $this->fine = $fine;
        $this->fine_number = $fine->fine_number;
        $this->member_id = $fine->member_id;
        $this->selectedMember = $fine->member ? $fine->member->full_name : '';
        $this->non_member_name = $fine->non_member_name;
        $this->amount = $fine->amount;
        $this->amount_paid = $fine->amount_paid;
        $this->change_amount = $fine->change_amount;
        $this->reason = $fine->reason;
        $this->charged_at = Carbon::parse($fine->charged_at)->format('Y-m-d');
        $this->is_paid = $fine->is_paid;
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
        $this->validate();
        $fine = $this->fine;
        $fine->update(array_merge($this->validate(), [
            'fine_number' => $fine->fine_number,
            'member_id' => $this->member_id,
            'user_id' => Auth::user()->id
        ]));
        session()->flash('success', 'Fine successfully updated');

        $this->reset();
    }
}
