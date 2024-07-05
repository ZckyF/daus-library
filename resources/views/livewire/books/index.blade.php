@push('styles')
    <style>
        .card-img-top {
            width: auto;
            height: 18rem;
            object-fit: cover;
            object-position: center;
        }
        .book-card {
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
        /* .card {
            transition: transform 0.2s ease-in-out, opacity 0.2s ease-in-out; 
        }
        .card:hover {
            transform: translateY(-10px);
            opacity: 0.8;
        } */

    </style>
@endpush


<div class="mt-5">
    <div class="row">
        <div class="col-md-4 col-12 mb-3">
            <div class="input-group">
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search book..." wire:model.live.debounce.300ms="search">
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
                <div class="dropdown-categories">
                    <select class="form-control rounded-4 shadow-sm" wire:model.live="category" style="cursor: pointer;">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="dropdown-sort">
                    <select class="form-control rounded-4 shadow-sm" wire:model.live="sortBy" style="cursor: pointer;">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                        <option value="title-asc">Title A-Z</option>
                        <option value="title-desc">Title Z-A</option>
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
                    <a wire:navigate href="{{ route('books.create') }}" class="btn btn-outline-primary fw-bold shadow-sm" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add book" >
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </div>
            
        </div>  
    </div>

    <div class="row mt-5" id="books-container">
        @if (session()->has('success'))
            <x-notifications.alert class="alert-success" :message="session('success')" />
        @endif

        @if (session()->has('error'))
            <x-notifications.alert class="alert-danger" :message="session('error')" />
        @endif
        
        <div class="selected-all">
            <input id="select-all" type="checkbox" class="form-check-input" wire:model="selectAllCheckbox" wire:click="toggleSelectAll">
            <label for="select-all" class="ms-1">Select All</label>
        </div>
        
        
        @if($books->isEmpty())
                <div class="col-12">
                    <p class="text-center">No books found.</p>
                </div>
            @else
            @foreach ($books as $book)
            @php
                $titleSlug = str_replace(' ', '-', $book->title);
                $authorSlug = str_replace(' ', '-', $book->author);
            @endphp
            <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4 g-2 book-card">
                <div class="card shadow-sm rounded-4 text-decoration-none" href="{{ route('books.edit', ['title' => $titleSlug, 'author' => $authorSlug]) }}">
                    <img loading="lazy" src="{{ asset('storage/covers/' . $book->cover_image_name) }}" class="card-img-top rounded-top-4" alt="{{ $book->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($book->title, 20) }}</h5>
                        <p class="card-text">{{ $book->author }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <input type="checkbox" class="form-check-input" wire:model.live="selectedBooks" value="{{ $book->id }}">
            
                        <div class="button-group">
                            <button type="button" class="btn btn-danger btn-sm text-white rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="setBookId({{ $book->id }})" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Delete book" >
                                <i class="bi bi-trash"></i>
                            </button>
                           
                            <a wire:navigate href="{{ route('books.edit', ['title' => $titleSlug, 'author' => $authorSlug]) }}" class="btn btn-info btn-sm text-white rounded-3" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Edit book">
                                <i class="bi bi-info-circle"></i>
                            </a>
                            <button type="button" class="btn btn-primary btn-sm text-white rounded-3" wire:click="setBookModalId({{ $book->id }})" data-bs-toggle="modal" data-bs-target="#addCartModal" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add to cart">
                                <i class="bi bi-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{ $books->links() }}
            
        @endif
    </div>
    <div class="row">
        <div class="d-flex justify-content-center mt-3">
            <div wire:loading wire:target="search,category,sortBy" class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    @include('livewire.books.modal-form')
    <x-notifications.modal title="Delete Confirmation" message="Are you sure you want to delete this book?" buttonText="Yes" action="delete" targetModal="deleteModal" />
    <x-notifications.modal title="Delete Selected Confirmation" message="Are you sure you want to delete these books?" buttonText="Yes" action="deleteSelected" targetModal="deleteSelectedModal" />
</div>

@push('scripts')

    <script>
        $(document).ready(() => {
            window.addEventListener('closeModal', () => {
                $('#deleteModal').modal('hide');
                $('#addCartModal').modal('hide');
                $('#deleteSelectedModal').modal('hide');
            })
        })
    </script>
    
@endpush

