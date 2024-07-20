
@push('styles')
    <style>
        .card-img-top {
            width: auto;
            height: 18rem;
            object-fit: cover;
            object-position: center;
        }
        .user-card {
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


<div class="mt-5">
    <div class="row">
        <div class="col-md-4 col-12 mb-3">
            <div class="input-group">
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search users..." wire:model.live.debounce.300ms="search">
            </div>
             
        </div>
        <div class="col-md-8 col-12">
            <div class="d-flex justify-content-sm-end justify-content-end align-items-md-end gap-3">
                @if ($showDeleteSelected)
                    <div class="button-delete-selected">
                        <button type="button" class="btn btn-danger fw-bold shadow-sm text-center rounded-4" data-bs-toggle="modal" data-bs-target="#deleteSelectedModal">
                            <i class="bi bi-trash"></i> 
                            Delete Selected | {{ count($selectedUsers) }}
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
                @can('create',  \App\Models\User::class)
                    <div class="button-add">
                        <a wire:navigate href="{{ route('users.create') }}"  class="btn btn-outline-primary fw-bold shadow-sm" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add user" >
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    </div> 
                @endcan
                
            </div>
            
        </div> 
    </div>
    <div class="row mt-5" id="users">
        @if (session()->has('success'))
            <x-notifications.alert class="alert-success" :message="session('success')" />
        @endif

        @if (session()->has('error'))
            <x-notifications.alert class="alert-danger" :message="session('error')" />
        @endif
        
        
        
        @if($users->isEmpty())
                <div class="col-12">
                    <p class="text-center">No data found.</p>
                </div>
            @else
            @foreach ($users as $user)
            @php
                $usernameSlug = str_replace(' ', '-', $user->username);
            @endphp
            <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4 g-2 user-card">
                <div class="card shadow-sm rounded-4 text-decoration-none">
                    <img loading="lazy" src="{{ asset('storage/avatars/' . $user->avatar_name) }}" class="card-img-top rounded-top-4" alt="{{ $user->username }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($user->username, 20) }}</h5>
                        <p class="card-text">
                            <span class="{{ $user->isOnline() ? 'text-success' : 'text-danger' }}">{{ $user->isOnline() ? 'Online' : 'Offline' }}</span>
                            <br>
                             {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </p>
                      
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                        
                        @can('delete', $user)
                            <input type="checkbox" class="form-check-input" wire:model.live="selectedUsers" value="{{ $user->id }}">
                        @else
                            <span></span>
                        @endcan
                        <div class="button-group">
                            @can('inActive', $user)      
                                <button type="button" class="btn btn-success btn-sm text-white rounded-3" data-bs-toggle="modal" data-bs-target="#isActiveModal" wire:click="setUserId({{ $user->id }})" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="{{ $user->is_actived ? 'Deactivate user' : 'Activate user' }}">
                                <i class="bi {{ $user->is_active ? 'bi-x-circle' : 'bi-check-circle' }}"></i>
                            </button>
                            @endcan
                            @can('delete', $user)
                                <button type="button" class="btn btn-danger btn-sm text-white rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="setUserId({{ $user->id }})" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Delete user">
                                <i class="bi bi-trash"></i>
                            </button>   
                            @endcan
                        
                            <a wire:navigate href="{{ route('users.edit', ['username' => $usernameSlug]) }}" class="btn btn-info btn-sm text-white rounded-3" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Edit user">
                                <i class="bi bi-info-circle"></i>
                            </a>
                        </div>
                           
                      
                    </div>
                </div>
            </div>
            @endforeach

            {{ $users->links() }}
            
        @endif
    </div>
    <div class="row">
        <div class="d-flex justify-content-center mt-3">
            <div wire:loading wire:target="search,sortBy,perPage" class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal"> 
        Are you sure you want to delete this user?
    </x-notifications.modal>
    <x-notifications.modal title="Delete Selected Confirmation" action="deleteSelected" targetModal="deleteSelectedModal">
        Are you sure you want to delete these users?    
    </x-notifications.modal>
    <x-notifications.modal title="{{ $user->is_active ? 'Deactivate' : 'Activate' }} Confirmation" action="toggleActive" targetModal="isActiveModal">
        Are you sure you want to {{ $user->is_active ? 'deactivate' : 'activate' }} this users?
    </x-notifications.modal>

    
</div>

@push('scripts')

    <script>

    </script>
    
@endpush