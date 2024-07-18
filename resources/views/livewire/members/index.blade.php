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
                            <i class="bi bi-trash"></i> 
                            Delete Selected | {{ count($selectedMembers) }}
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
                @can('create', \App\Models\Member::class)
                    <div class="button-add">
                        <a wire:navigate href="{{ route('members.create') }}"  class="btn btn-outline-primary fw-bold shadow-sm" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add member" >
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    </div>
                @endcan
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
        
        
        
        @if($members->isEmpty())
                <div class="col-12">
                    <p class="text-center">No data found.</p>
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
                        @can('delete', $member)
                            <input type="checkbox" class="form-check-input" wire:model.live="selectedMembers" value="{{ $member->id }}">
                        @else
                        <span></span>
                        @endcan
                       
            
                        <div class="button-group">
                            @can('delete', $member)
                                <button type="button" class="btn btn-danger btn-sm text-white rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="setMemberId({{ $member->id }})" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Delete member" >
                                <i class="bi bi-trash"></i>
                            </button>  
                            @endcan
                            @can('view', $member)
                            <a wire:navigate href="{{ route('members.edit', ['number_card' => $member->number_card]) }}" class="btn btn-info btn-sm text-white rounded-3" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Edit member">
                                <i class="bi bi-info-circle"></i>
                            </a>
                            @endcan
                           
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
            <div wire:loading wire:target="search,sortBy,perPage" class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <x-notifications.modal title="Delete Confirmation" buttonText="Yes" action="delete" targetModal="deleteModal"> 
        Are you sure you want to delete this member?
    </x-notifications.modal>
    <x-notifications.modal title="Delete Selected Confirmation" buttonText="Yes" action="deleteSelected" targetModal="deleteSelectedModal">
        Are you sure you want to delete these members?    
    </x-notifications.modal>

    
</div>

@push('scripts')

    <script>

    </script>
    
@endpush