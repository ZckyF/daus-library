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
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search fines..." wire:model.live.debounce.300ms="search">
            </div>
        </div>
        <div class="col-md-8 col-12">
            <div class="d-flex justify-content-sm-end justify-content-end align-items-md-end gap-2">
                @if ($showDeleteSelected)
                    <div class="button-delete-selected">
                        <button type="button" class="btn btn-danger fw-bold shadow-sm text-center rounded-4" data-bs-toggle="modal" data-bs-target="#deleteSelectedModal">
                            <i class="bi bi-trash"></i> 
                            Delete Selected | {{ count($selectedFines) }}
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
                @can('create', \App\Models\Fine::class)
                    <div class="button-add">
                        <a wire:navigate href="{{ route('fines.create') }}" class="btn btn-outline-primary fw-bold shadow-sm text-center" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add fine">
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
            $columns = Auth::user()->cannot('delete',$fines[0]) ? Arr::except($columns,0) : $columns;
        @endphp
        <x-tables.table tableClass="table-striped shadow-sm" :columns="$columns" :useCheckboxColumn="true"> 
            @if($fines->isEmpty())
                <tr>
                    <td colspan="{{ count($columns) }}" class="text-center">No data found.</td>
                </tr>
            @else
                @foreach ($fines as $index => $fine)
                    <tr>
                        @can('delete', $fine)
                            <td>
                                <input type="checkbox" class="form-check-input" wire:model.live="selectedFines" value="{{ $fine->id }}">
                            </td>
                        @endcan
                        <td>{{ $fines->firstItem() + $index }}</td>
                        <td>{{ $fine->fine_number }}</td>
                        <td>{{ $fine->member ? Str::limit($fine->member->full_name, 40) : '' }}</td>
                        <td>{{ Str::limit($fine->non_member_name, 40) }}</td>
                        <td>{{ 'Rp. ' . number_format($fine->amount, 2, ',', '.') }}</td>
                        <td>{{ 'Rp. ' . number_format($fine->amount_paid, 2, ',', '.') }}</td>
                        <td>{{ $fine->is_paid ? 'Yes' : 'No' }}</td>
                        <td>{{ $fine->user->username}}</td>
                        <td>
                            <a wire:navigate href="{{ route('fines.edit', $fine->fine_number) }}" class="btn btn-info btn-sm rounded-3 text-white" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Edit fine">
                                <span><i class="bi bi-info-circle"></i></span>
                            </a>
                            @can('delete', $fine)
                                <button class="btn btn-danger btn-sm rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="setFineId({{ $fine->id }})" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Delete fine">
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
        {{ $fines->links() }}
    </div>

    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal">
        Are you sure you want to delete this fine?
    </x-notifications.modal>
    <x-notifications.modal title="Delete Selected Confirmation" action="deleteSelected" targetModal="deleteSelectedModal"> 
        Are you sure you want to delete these fines?
    </x-notifications.modal>
</div>


@push('scripts')
    <script>
    </script>
@endpush
