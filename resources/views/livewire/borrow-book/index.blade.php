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
        <div class="col-lg-4 col-12 mb-3">
            <div class="input-group">
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search borrow books..." wire:model.live.debounce.300ms="search">
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="d-flex justify-content-sm-end justify-content-end align-items-md-end gap-2">
                @if ($showDeleteSelected)
                    <div class="button-delete-selected">
                        <button type="button" class="btn btn-danger fw-bold shadow-sm text-center rounded-4" data-bs-toggle="modal" data-bs-target="#deleteSelectedModal">
                            <i class="bi bi-trash"></i> 
                            Delete Selected | {{ count($selectedBorrowBooks) }}
                        </button>
                    </div>
                @endif
                <div class="filter-date">
                    <button class="btn btn-primary bg-white shadow-sm border-0 rounded-4" data-bs-toggle="modal" data-bs-target="#filterDateModal">
                        <span><i class="bi bi-filter"></i></span>
                        <span>Filter Date</span>
                    </button>
                </div>
                <div class="select-sort">
                    <select class="form-control rounded-4 shadow-sm" wire:model.change="sortBy" style="cursor: pointer;">
                        @foreach ($optionSorts as $sort => $value)
                            <option value="{{ $sort }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="select-per-page">
                    <select class="form-control rounded-4 shadow-sm" wire:model.change="perPage" style="cursor: pointer;">
                        @foreach ($optionPages as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                @can('create', \App\Models\BookCategory::class)
                    <div class="button-add">
                        <a wire:navigate href="{{ route('carts') }}" class="btn btn-outline-primary fw-bold shadow-sm text-center" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add book category">
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
        @php
           $columns = Auth::user()->cannot('delete',$borrowBooks[0]) ? Arr::except($columns,0) : $columns;
        @endphp

        <x-tables.table tableClass="table-striped shadow-sm" :columns="$columns">
            @if($borrowBooks->isEmpty())
                <tr>
                    <td colspan="{{ count($columns) }}" class="text-center">No data found.</td>
                </tr>
            @else
                @foreach ($borrowBooks as $index => $borrowBook)
                    <tr>
                        @can('delete', $borrowBook)
                            <td>
                                <input type="checkbox" class="form-check-input" wire:model.live="selectedBorrowBooks" wire:key="book-category-{{ $borrowBook->id }}" value="{{ $borrowBook->id }}">
                            </td> 
                        @endcan
                        
                        <td>{{ $borrowBooks->firstItem() + $index }}</td>
                        <td>{{ $borrowBook->borrow_number }}</td>
                        <td>{{ $borrowBook->member->full_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($borrowBook->borrow_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($borrowBook->due_date)->format('Y-m-d') }}</td>
                        <td>{{ \Carbon\Carbon::parse($borrowBook->return_date)->format('Y-m-d') }}</td>
                        <td>{{ $borrowBook->status }}</td>
                        <td>{{ $borrowBook->user->username }}</td>
                        <td>
                            <a wire:navigate href="{{ route('borrow-books.edit',['borrow_number' => $borrowBook->borrow_number]) }}" class="btn btn-info btn-sm rounded-3 text-white" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Edit Borrow Book">
                                <span><i class="bi bi-info-circle"></i></span>
                            </a>
                            @can('delete', $borrowBook)
                                <button class="btn btn-danger btn-sm rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="setBorrowBookId({{ $borrowBook->id }})" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Delete Borrow Book">
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
        {{ $borrowBooks->links() }}
    </div>
    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal"> 
        Are you sure you want to delete this category?
    </x-notifications.modal>
    <x-notifications.modal title="Delete Selected Confirmation" action="deleteSelected" targetModal="deleteSelectedModal"> 
        Are you sure you want to delete these categories?
    </x-notifications.modal>
    <x-notifications.modal title="Filter Date" action="filterDate" targetModal="filterDateModal" buttonText="Filter">
        <div class="d-flex justify-content-end">
            <div class="reset-form ">
                <button class="btn btn-primary btn-sm rounded-3 text-white" wire:click="resetFilter">
                    <span wire:loading.remove wire:target="resetFilter"><i class="bi bi-arrow-clockwise"></i></span>
                    <span wire:loading wire:target="resetFilter" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> 
                    Reset
                </button>
            </div> 
        </div>
        
        <div class="form-group mb-3">
            <label for="borrowDateFrom">Borrow Date (From - To)</label>
            <div class="d-flex">
                <input type="date" id="borrowDateFrom" wire:model="borrowDateFrom" class="form-control me-2">
                <input type="date" id="borrowDateTo" wire:model="borrowDateTo" class="form-control">
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="returnDateFrom">Return Date (From - To)</label>
            <div class="d-flex">
                <input type="date" id="returnDateFrom" wire:model="returnDateFrom" class="form-control me-2">
                <input type="date" id="returnDateTo" wire:model="returnDateTo" class="form-control">
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="returnedDateFrom">Returned Date (From - To)</label>
            <div class="d-flex">
                <input type="date" id="returnedDateFrom" wire:model="returnedDateFrom" class="form-control me-2">
                <input type="date" id="returnedDateTo" wire:model="returnedDateTo" class="form-control">
            </div>
        </div>
    </x-notifications.modal>
    
</div>
