<?php

namespace App\Livewire\Forms;

use App\Models\Employee;
use Illuminate\Validation\Rule;
use Livewire\Form;

class EmployeeForm extends Form
{
    /**
     * Optional existing Employee model instance.
     * 
     * @var Employee|null
     */
    public ?Employee $employee = null;
    /**
     * The full_name for the form
     * 
     * @var string
     */
    public $full_name;
    /**
     * The email for the form
     * 
     * @var string
     */
    public $email;
    /**
     * The address for the form
     * 
     * @var string
     */
    public $address;
    /**
     * The nik for the form
     * 
     * @var string
     */
    public $nik;
    /**
     * The phone_number for the form
     * 
     * @var string
     */
    public $phone_number;

    /**
     * Rules for the form validation.
     * 
     * @return array
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|max:255',
            'email' => 'required|email|string|max:255',
            'address' => 'required|string',
            'nik' => 'required|numeric|digits:16',
            'phone_number' => 'required|numeric|digits_between:10,15|alpha_dash',
        ];
    }

    /**
     * Set the current employee for the form.
     * 
     * @param Employee $employee
     * @return void
     */
    public function setEmployee(Employee $employee): void
    {
        $this->employee = $employee;

        $this->full_name = $employee->full_name;
        $this->email = $employee->email;
        $this->address = $employee->address;
        $this->nik = $employee->nik;
        $this->phone_number = $employee->phone_number;
    }


    public function store(): void
    {
        $rules = $this->rules();
        $rules['email'] .= '|unique:employees,email';
        $rules['nik'] .= '|unique:employees,nik';
        $rules['phone_number'] .= '|unique:employees,phone_number';

        Employee::create($this->validate($rules));

        $this->reset();

        session()->flash('success', 'Employee created successfully.');
    }

    public function update()
    {
        $employee = $this->employee;
        $rules = $this->rules();
        $rules['email'] .= '|'. Rule::unique('employees', 'email')->ignore($employee);
        $rules['nik'] .= '|'.Rule::unique('employees', 'nik')->ignore($employee);
        $rules['phone_number'] .= '|'.Rule::unique('employees', 'phone_number')->ignore($employee);

        $employee->update($this->validate($rules));
        $this->reset();
        session()->flash('success', 'Employee updated successfully.');

        


    }
}
