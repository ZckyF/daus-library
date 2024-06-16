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
            cursor: pointer;
        }
        .button-add:hover a {
            color: white;
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .card {
            transition: transform 0.2s ease-in-out, opacity 0.2s ease-in-out; 
        }
        .card:hover {
            transform: translateY(-10px);
            opacity: 0.8;
        }

    </style>
@endpush


<div class="mt-5">
    <div class="row">
        <!-- Input Search -->
        <div class="col-md-4 col-sm-5 col-12 mb-3">
            <div class="input-group">
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search book..." wire:model.live.debounce.300ms="search">
            </div>
        </div>
        <div class="col-md-8 col-sm-7 col-12 jus">
            <div class="d-flex justify-content-sm-end justify-content-center align-items-md-end gap-3">
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

                <div class="button-add">
                    <a href="{{ route('books.create') }}" class="btn btn-outline-primary fw-bold shadow-sm">
                        <i class="bi bi-plus-lg"></i>
                    </a>
                </div>
            </div>
            
        </div>
    </div>

    <div class="row mt-5" id="books-container">
        @if($books->isEmpty())
            <div class="col-12">
                <p class="text-center">No books found.</p>
            </div>
        @else
            @foreach ($books as $book)
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4 g-2 book-card">
                    <div class="card shadow-sm rounded-4">
                        <img src="{{ asset('storage/covers/' . $book->cover_image_name) }}" class="card-img-top rounded-top-4" alt="{{ $book->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($book->title, 20) }}</h5>
                            <p class="card-text">{{ $book->author }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div wire:loading wire:target="loadMore" class="text-center mt-2">
        <p>Loading...</p>
    </div>
</div>
