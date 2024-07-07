@push('styles')
@push('styles')
    <style>
        .card-img-top {
            width: auto;
            height: 18rem;
            object-fit: cover;
            object-position: center;
        }
        .member-card {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeInUp 0.5s forwards;
            /* cursor: pointer; */
        }
        .button-add:hover a {
            color: white;
        }
        .button-add:active a {
            color: white !important;
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
@endpush
@endpush

<div class="mt-5">
    <div class="row">
        <div class="col-md-4 col-12 mb-3">
            <div class="input-group">
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search members..." wire:model.live.debounce.300ms="search">
            </div>
             
        </div>
        <div class="col-md-8 col-12">
            <div class="d-flex justify-content-sm-end justify-content-end align-items-md-end gap-3">
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
                    <a wire:navigate href="{{ route('members.create') }}"  class="btn btn-outline-primary fw-bold shadow-sm" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add member" >
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </div>
            
        </div> 
    </div>
    <div class="row mt-5" id="members">
        @if (session()->has('success'))
            <x-notifications.alert class="alert-success" :message="session('success')" />
        @endif

        @if (session()->has('error'))
            <x-notifications.alert class="alert-danger" :message="session('error')" />
        @endif
        
        <div class="selected-all">
            <span class="spinner-border text-primary spinner-border-sm " wire:loading wire:target="toggleSelectAll"></span>
            <input id="select-all" type="checkbox" class="form-check-input" wire:loading.remove wire:target="toggleSelectAll" wire:model="selectAllCheckbox" wire:click="toggleSelectAll" />
            
            <label for="select-all" class="ms-1">Select All</label>
            
        </div>
        
        
        @if($members->isEmpty())
                <div class="col-12">
                    <p class="text-center">No books found.</p>
                </div>
            @else
            @foreach ($members as $member)

            <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4 g-2 member-card">
                <div class="card shadow-sm rounded-4 text-decoration-none">
                    <img loading="lazy" src="{{ asset('storage/members/' . $member->image_name) }}" class="card-img-top rounded-top-4" alt="{{ $member->full_name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($member->full_name, 20) }}</h5>
                        <p class="card-text">
                            {{ $member->number_card }}
                            <br>
                            {{ $member->email }}
                        </p>
                      
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <input type="checkbox" class="form-check-input" wire:model.live="selectedMembers" value="{{ $member->id }}">
            
                        <div class="button-group">
                            <button type="button" class="btn btn-danger btn-sm text-white rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="setBookId({{ $member->id }})" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Delete book" >
                                <i class="bi bi-trash"></i>
                            </button>
                           
                            <a wire:navigate href="{{ route('members.edit', ['number_card' => $member->number_card]) }}" class="btn btn-info btn-sm text-white rounded-3" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Edit book">
                                <i class="bi bi-info-circle"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{ $members->links() }}
            
        @endif
    </div>
    <div class="row">
        <div class="d-flex justify-content-center mt-3">
            <div wire:loading wire:target="search,sortBy" class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <x-notifications.modal title="Delete Confirmation" message="Are you sure you want to delete this book?" buttonText="Yes" action="delete" targetModal="deleteModal" />
    <x-notifications.modal title="Delete Selected Confirmation" message="Are you sure you want to delete these books?" buttonText="Yes" action="deleteSelected" targetModal="deleteSelectedModal" />
    <div class="row"></div>
    
</div>

@push('scripts')

    <script>

    </script>
    
@endpush