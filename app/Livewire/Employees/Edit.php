<?php

namespace App\Livewire\Employees;

use App\Livewire\Forms\EmployeeForm;
use App\Models\Employee;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Employee')]
class Edit extends Component
{
    /**
     * Form instance of EmployeeForm
     * 
     * @var EmployeeForm
     */
    public EmployeeForm $form;
    /**
     * Initialize the component with a nik.
     * 
     * @param string $nik
     * @return void
     */
    public function mount(string $nik): void
    {
        $employee = Employee::where('nik', $nik)->firstOrFail();
        $this->form->setEmployee($employee);
        if (!$employee) {
            abort(404);
        }
        if(Gate::denies('view', $this->form->employee)) {
            abort(403);
        }
    }

     /**
     * Save the updated employee.
     * 
     * @return void
     */
    public function save(): void
    {
        if(Gate::denies('update', $this->form->employee)) {
            abort(403);
        }
        $this->form->update();
        $this->redirectRoute('employees');
    }

    /**
     * Delete the current employee.
     * 
     * @return void
     */
    public function delete(): void
    {
        if(Gate::denies('update', $this->form->employee)) {
            abort(403);
        }
        $employee = Employee::find($this->form->employee->id);
        $employee->delete();
            
        session()->flash('success', 'Employee successfully deleted');

        $this->redirectRoute('employees');
    }

    /**
     * Render the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $isEditPage = true;
        return view('livewire.employees.edit',compact('isEditPage'));
    }
}
