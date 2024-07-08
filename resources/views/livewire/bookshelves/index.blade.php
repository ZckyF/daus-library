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
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search bookshelves..." wire:model.live.debounce.300ms="search">
            </div>
        </div>
        <div class="col-md-8 col-12">
            <div class="d-flex justify-content-sm-end justify-content-end align-items-md-end gap-2">
                @if ($showDeleteSelected)
                    <div class="button-delete-selected">
                        <button type="button" class="btn btn-danger fw-bold shadow-sm text-center rounded-4" data-bs-toggle="modal" data-bs-target="#deleteSelectedModal">
                            <i class="bi bi-trash"></i> Delete Selected
                        </button>
                    </div>
                @endif
                <div class="dropdown-sort">
                    <select class="form-control rounded-4 shadow-sm" wire:model.live="sortBy" style="cursor: pointer;">
                        @foreach ($optionSorts as $sort => $value)
                            <option value="{{ $sort }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="dropdown-per-page">
                    <select class="form-control rounded-4 shadow-sm" wire:model.live="perPage" style="cursor: pointer;">
                        @foreach ($optionPages as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="button-add">
                    <a wire:navigate href="{{ route('bookshelves.create') }}" class="btn btn-outline-primary fw-bold shadow-sm text-center" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add bookshelf">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="data-table">
        @if (session()->has('success'))
            <x-notifications.alert class="alert-success" :message="session('success')" />
        @endif
        <x-tables.table tableClass="table-striped shadow-sm" :columns="$columns" :useCheckboxColumn="true"> 
            @if($bookshelves->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">No data found.</td>
                </tr>
            @else
                @foreach ($bookshelves as $index => $bookshelf)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input" wire:model.live="selectedBookshelves" value="{{ $bookshelf->id }}">
                        </td>
                        <td>{{ $bookshelves->firstItem() + $index }}</td>
                        <td>{{ $bookshelf->bookshelf_number }}</td>
                        <td>{{ $bookshelf->user->username }}</td>
                        <td>
                            <a wire:navigate href="{{ route('bookshelves.edit', $bookshelf->bookshelf_number) }}" class="btn btn-info btn-sm rounded-3 text-white" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Edit bookshelf">
                                <span><i class="bi bi-info-circle"></i></span>
                            </a>
                            <button class="btn btn-danger btn-sm rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="setBookShelfId({{ $bookshelf->id }})" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Delete bookshelf">
                                <span><i class="bi bi-trash"></i></span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
        
        </x-tables.table>
        <div class="d-flex justify-content-center mt-3">
            <div wire:loading wire:target="search,sortBy" class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        {{ $bookshelves->links() }}
    </div>

    <x-notifications.modal title="Delete Confirmation" message="Are you sure you want to delete this bookshelf?" buttonText="Yes" action="delete" targetModal="deleteModal" />
    <x-notifications.modal title="Delete Selected Confirmation" message="Are you sure you want to delete these bookshelves?" buttonText="Yes" action="deleteSelected" targetModal="deleteSelectedModal" />
</div>


@push('scripts')
    <script>
    </script>
@endpush