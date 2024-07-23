<?php

namespace App\Livewire\Employees;

use App\Models\Employee;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Employees')]
class Index extends Component
{
    use WithPagination;
    /**
     * Search term for the employees.
     * 
     * @var string
     */
    public string $search = '';

    /**
     * Sort order.
     * 
     * @var string
     */
    public string $sortBy = 'newest';

    /**
     * Number of items per page.
     * 
     * @var int
     */
    public int $perPage = 10;
    /**
     * ID of the selected employee.
     * 
     * @var int
     */
    public int $employeeId;

    /**
     * List of selected employees.
     * 
     * @var array
     */
    public array $selectedEmployees = [];

    /**
     * Flag to show or hide the delete selected button.
     * 
     * @var bool
     */
    public bool $showDeleteSelected = false;

    /**
     * Reset pagination on search update.
     * 
     * @return void
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination on sort order update.
     * 
     * @return void
     */
    public function updatedSortBy(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination on per page update.
     * 
     * @return void
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Toggle the show delete selected button.
     * 
     * @return void
     */
    public function updatedSelectedEmployees(): void
    {
        $this->showDeleteSelected = !empty($this->selectedEmployees);
    }

    /**
     * Set the employee ID.
     * 
     * @param int $employeeId
     * @return void
     */
    public function setEmployeeId($employeeId): void
    {
        $this->employeeId = $employeeId;
    }

    /**
     * Delete a employee by its ID.
     * 
     * @return void
     */
    public function delete(): void
    {
        $employee = Employee::find($this->employeeId);
        if (Gate::denies('delete', $employee)) {
            abort(403);
        }
        $employee->delete();
            
        session()->flash('success', 'Employee successfully deleted');
    
        $this->dispatch('closeModal');
    }

    /**
     * Delete selected employees.
     * 
     * @return void
     */
    public function deleteSelected(): void
    {
        $employees = Employee::find($this->selectedEmployees);
        if (Gate::denies('delete', $employees[0])) {
            abort(403);
        }
        foreach ($employees as $employee) {
            $employee->delete();
        }

        $this->selectedEmployees = [];
        session()->flash('success', 'Employee successfully deleted');
        $this->dispatch('closeModal');
    }

    /**
     * Fetch employees based on search and sort criteria.
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function fetchEmployees(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Employee::query();



        if ($this->search) {
            $query->where(function ($query) {
                $query->where('full_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }
        $query->whereDoesntHave('user', function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', 'super_admin');
            });
        });
        

        switch ($this->sortBy) {
            case 'fullname-asc':
                $query->orderBy('full_name', 'asc');
                break;
            case 'fullname-desc':
                $query->orderBy('full_name', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
        }

        return $query->paginate($this->perPage);
    }
    /**
     * Render the component.
     * 
     * @return \Illuminate\View\View
     */
    public function render(): \Illuminate\View\View
    {
        $employees = $this->fetchEmployees();
        $optionPages = [10, 20, 40, 50, 100];
        $columns = ['','#','NIK','Full Name','Email','Actions'];
        $optionSorts = [
            'newest' => 'Newest',
            'oldest' => 'Oldest',
            'fullname-asc' => 'Full Name A-Z',
            'fullname-desc' => 'Full Name Z-A'
        ];
        return view('livewire.employees.index',compact('employees','optionPages','columns','optionSorts'));
    }
}
