@push('styles')
    <style>
        .card-img-top {
            width: auto;
            height: 18rem;
            object-fit: cover;
        }
    </style>
@endpush

<div class="mt-5">
    <div class="row">
        <!-- Input Seacrh -->
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" class="form-control rounded-4 shadow-sm" placeholder="Search book..." wire:model.live="search">
            </div>
        </div>
        <!-- Dropdown Categories -->
        <div class="col-md-3">
            <select class="form-control rounded-4 shadow-sm" wire:model="category">
                <option value="">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
        </div>
        <!-- Dropdown Sortir -->
        <div class="col-md-3">
            <select class="form-control rounded-4 shadow-sm" wire:model="sortBy">
                <option value="title-asc">Title A-Z</option>
                <option value="title-desc">Title Z-A</option>
                <option value="newest">Newest</option>
                <option value="oldest">Oldest</option>
            </select>
        </div>
    </div>
    <div class="row mt-5" id="books-container">
        @foreach ($books as $book)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 book-card">
                <div class="card" style="max-height: 19rem">
                    <img src="{{ asset('storage/covers/' . $book->cover_image_name) }}" class="card-img-top" alt="{{ $book->title }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $book->title }}</h5>
                        <p class="card-text">{{ $book->author }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
