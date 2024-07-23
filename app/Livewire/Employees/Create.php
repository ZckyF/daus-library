<?php

namespace App\Livewire\Employees;

use App\Livewire\Forms\EmployeeForm;
use App\Models\Employee;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Create Employee')]
class Create extends Component
{
    /**
     * Form instance of EmployeeForm
     * 
     * @var EmployeeForm
     */
    public EmployeeForm $form;
    /**
     * Save the new employee.
     * 
     * @return void
     */
    public function save(): void
    {
        if(Gate::denies('create', Employee::class)) {
            abort(403);
        }
        $this->form->store();
        $this->redirectRoute('employees');
    }
    /**
     * Render the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.employees.create');
    }
}
