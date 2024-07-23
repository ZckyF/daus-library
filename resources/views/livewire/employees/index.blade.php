@push('styles')
    <style>
        .button-add:hover a {
            color: white;
        }
        .button-add:active a{
            color: white !important;
        }
    </style>
@endpush

<div class="mt-5">
    <div class="row mb-3">
        <div class="col-md-4 col-12 mb-3">
            <div class="input-group">
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search employees..." wire:model.live.debounce.300ms="search">
            </div>
        </div>
        <div class="col-md-8 col-12">
            <div class="d-flex justify-content-sm-end justify-content-end align-items-md-end gap-2">
                @if ($showDeleteSelected)
                    <div class="button-delete-selected">
                        <button type="button" class="btn btn-danger fw-bold shadow-sm text-center rounded-4" data-bs-toggle="modal" data-bs-target="#deleteSelectedModal">
                            <i class="bi bi-trash"></i> 
                            Delete Selected | {{ count($selectedEmployees) }}
                        </button>
                    </div>
                @endif
                <div class="dropdown-sort">
                    <select class="form-control rounded-4 shadow-sm" wire:model.change="sortBy" style="cursor: pointer;">
                        @foreach ($optionSorts as $sort => $value)
                            <option value="{{ $sort }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="dropdown-per-page">
                    <select class="form-control rounded-4 shadow-sm" wire:model.change="perPage" style="cursor: pointer;">
                        @foreach ($optionPages as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                @can('create', \App\Models\Employee::class)
                    <div class="button-add">
                        <a wire:navigate href="{{ route('employees.create') }}" class="btn btn-outline-primary fw-bold shadow-sm text-center" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add employee">
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <div class="data-table">
        @if (session()->has('success'))
            <x-notifications.alert class="alert-success" :message="session('success')" />
        @endif
        <x-tables.table tableClass="table-striped shadow-sm" :columns="$columns" :useCheckboxColumn="true"> 
            @if($employees->isEmpty())
                <tr>
                    <td colspan="{{ count($columns) }}" class="text-center">No data found.</td>
                </tr>
            @else
                @foreach ($employees as $index => $employee)
                    <tr>
                        @can('delete', $employee)
                            <td>
                                <input type="checkbox" class="form-check-input" wire:model.live="selectedEmployees" value="{{ $employee->id }}">
                            </td>
                        @else
                            <td></td>
                        @endcan
                        <td>{{ $employees->firstItem() + $index }}</td>
                        <td>{{ $employee->nik }}</td>
                        <td>{{ $employee->full_name }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>
                            @can('view', $employee)
                                <a wire:navigate href="{{ route('employees.edit', $employee->nik) }}" class="btn btn-info btn-sm rounded-3 text-white" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Edit employee">
                                <span><i class="bi bi-info-circle"></i></span>
                                </a>
                            @endcan
                            @can('delete', $employee)
                                <button class="btn btn-danger btn-sm rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="setEmployeeId({{ $employee->id }})" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Delete employee">
                                    <span><i class="bi bi-trash"></i></span>
                                </button> 
                            @endcan
                            
                        </td>
                    </tr>
                @endforeach
            @endif
        
        </x-tables.table>
        <div class="d-flex justify-content-center mt-3">
            <div wire:loading wire:target="search,sortBy,perPage" class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        {{ $employees->links() }}
    </div>

    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal">
        Are you sure you want to delete this employee?
    </x-notifications.modal>
    <x-notifications.modal title="Delete Selected Confirmation" action="deleteSelected" targetModal="deleteSelectedModal"> 
        Are you sure you want to delete these employees?
    </x-notifications.modal>
</div>


@push('scripts')
    <script>
    </script>
@endpush
