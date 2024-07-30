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

        .dropdown-item:hover {
            cursor: pointer;
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
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search books..." wire:model.live.debounce.300ms="search">
            </div>
        </div>
        <div class="col-md-8 col-12 mb-3">
            <div class="d-flex justify-content-sm-end justify-content-end align-items-md-end gap-3">
                <div class="dropdown-bookshelves">
                    <div class="dropdown">
                        <button class="btn bg-white shadow-sm rounded-4 dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Bookshelf
                        </button>
                        <div class="dropdown-menu p-3" style="max-height: 400px; overflow-y: auto;" wire:ignore.self aria-labelledby="dropdownMenuButton">
                            <input type="text" class="form-control mb-2" placeholder="Search bookshelf..." wire:model.live.debounce.300ms="searchBookshelves">
                            @if($bookshelves->count() <= 0)
                                <div class="dropdown-item">Not found</div>
                                
                            @else
                                <div class="dropdown-item" wire:click="selectBookshelf(0)">All</div>
                                @foreach ($bookshelves as $bookshelf)
                                <div class="dropdown-item {{ $bookshelfId == $bookshelf->id ? 'active rounded-1' : '' }} " wire:click="selectBookshelf('{{ $bookshelf->id }}')">{{ Str::limit($bookshelf->bookshelf_number, 15) }}</div>
                                @endforeach
                            @endif
                            
                        </div>
                    </div>
                    
                </div>
                <div class="dropdown-book-category">
                    <div class="dropdown">
                        <button class="btn bg-white shadow-sm rounded-4 dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Category
                        </button>
                        <div class="dropdown-menu p-3" style="max-height: 400px; overflow-y: auto;" wire:ignore.self aria-labelledby="dropdownMenuButton">
                            <input type="text" class="form-control mb-2" placeholder="Search category..." wire:model.live.debounce.300ms="searchBookCategory">
                            @if($bookCategories->count() <= 0)
                                <div class="dropdown-item">Not found</div>
                                
                            @else
                                <div class="dropdown-item" wire:click="selectBookCategory(0)">All</div>
                                @foreach ($bookCategories as $category)
                                <div class="dropdown-item {{ $bookCategoryId == $category->id ? 'active rounded-1' : '' }} " wire:click="selectBookCategory('{{ $category->id }}')">{{ Str::limit($category->category_name, 15) }}</div>
                                @endforeach
                            @endif
                            
                        </div>
                    </div>
                    
                </div>
                
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
                @can('create', \App\Models\Book::class)
                    <div class="button-add">
                        <a wire:navigate href="{{ route('books.create') }}" class="btn btn-outline-primary fw-bold shadow-sm" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add book" >
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    </div>  
                @endcan
                
            </div>
            
        </div> 
        <div class="col-12 justify-content-end align-items-end">
            @if ($showDeleteSelected)
                    <div class="button-delete-selected">
                        <button type="button" class="btn btn-danger fw-bold shadow-sm text-center rounded-4" data-bs-toggle="modal" data-bs-target="#deleteSelectedModal">
                            <i class="bi bi-trash"></i> 
                            Delete Selected | {{ count($selectedBooks) }}
                        </button>
                    </div>
                @endif
        </div> 
    </div>

    <div class="row mt-5" id="books-container">
        @if (session()->has('success'))
            <x-notifications.alert class="alert-success" :message="session('success')" />
        @endif

        @if (session()->has('error'))
            <x-notifications.alert class="alert-danger" :message="session('error')" />
        @endif
        
        
        @if($books->isEmpty())
                <div class="col-12">
                    <p class="text-center">No books found.</p>
                </div>
            @else
            @foreach ($books as $book)
            <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4 g-2 book-card">
                <div class="card shadow-sm rounded-4 text-decoration-none">
                    <img loading="lazy" src="{{ asset('storage/covers/' . $book->cover_image_name) }}" class="card-img-top rounded-top-4" alt="{{ $book->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($book->title, 20) }}</h5>
                        <p class="card-text">{{ $book->author }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                        @can('delete',$book)
                            <input type="checkbox" class="form-check-input" wire:model.live="selectedBooks" wire:key="book-{{ $book->id }}"  @if(in_array($book->id, $selectedBooks)) checked @endif value="{{ $book->id }}">  

                        @else
                            <span></span>
                        @endcan

                        
                        <div class="button-group">
                            @can('delete',$book)
                                <button type="button" class="btn btn-danger btn-sm text-white rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal" wire:click="setBookId({{ $book->id }})" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Delete book" >
                                <i class="bi bi-trash"></i>
                            </button> 
                            @endcan
                          
                            <a wire:navigate href="{{ route('books.edit', ['isbn' => $book->isbn]) }}" class="btn btn-info btn-sm text-white rounded-3" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Edit book">
                                <i class="bi bi-info-circle"></i>
                            </a>   
                            
                            @can('create',\App\Models\BorrowBook::class)
                                <button type="button" class="btn btn-primary btn-sm text-white rounded-3" wire:click="setBookModalId({{ $book->id }})" data-bs-toggle="modal" data-bs-target="#addCartModal" data-tooltip="tooltip" data-bs-placement="top" data-bs-title="Add to cart">
                                    <i class="bi bi-cart"></i>
                                </button>  
                            @endcan
                            
                            
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
            <div wire:loading wire:target="search,category,sortBy,perPage" class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    {{-- @include('livewire.books.modal-form') --}}
    <x-notifications.modal title="Add to cart" buttonText="Add" action="addToCart" targetModal="addCartModal">
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" min="1" max="3" class="form-control" id="quantity" wire:model.number="quantity">
            @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <input type="hidden" wire:model="bookModalId">
    </x-notifications.modal>

    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal"> 
        Are you sure you want to delete this book?
    </x-notifications.modal>
    <x-notifications.modal title="Delete Selected Confirmation"  action="deleteSelected" targetModal="deleteSelectedModal"> 
        Are you sure you want to delete these books?
    </x-notifications.modal>
</div>

@push('scripts')

    <script>
    </script>
    
@endpush

