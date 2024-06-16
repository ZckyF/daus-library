@push('styles')
    <style>
        .cover-image {
            position: relative;
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .cover-image img {
            object-fit: contain;
            border-radius: 10px;
            background-position: center;
            max-height: 300px; 
        }
        .cover-image  .icon-image {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            background-color: rgba(0, 0, 0, 0.7);
            width: 100%;
            height: 100%;
            top: 100%;
            transition: top 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }
        .cover-image .icon-image {
            top: 0;
            opacity: 1;
        }
    </style>
@endpush

<div class="mt-5">
    <h3 class="mb-4">Create New Book</h3>
    @if (session()->has('message'))
        <div class="alert alert-success">
             {{ session('message') }}
        </div>
    @endif
    <form wire:submit.prevent="save" class="form-floating">
        <div class="row">
            <div class="mb-3 col-md-12">
                <label for="cover_image" class="form-label">
                    <div class="cover-image">
                        <img src="{{ $selectedImage ? $selectedImage : 'default.jpg' }}" alt="" alt="cover-book">
                        <span class="icon-cover">
                            <i class="bi bi-plus-lg add-image-icon"></i>
                        </span>
                        <input type="file" class="form-control file-cover" id="cover_image" wire:model="selectedImage" accept="image/png, image/gif, image/jpeg" style="display: none;">
                        @error('cover_image') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>  
                </label>   
            </div>
            
            <div class="mb-3 col-md-4">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control" id="isbn" wire:model="isbn">
                        @error('isbn') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-4">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" wire:model="title">
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-4">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" wire:model="author">
                        @error('author') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-6">
                        <label for="published_year" class="form-label">Published Year</label>
                        <input type="number" class="form-control" id="published_year" wire:model="published_year">
                        @error('published_year') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-6">
                        <label for="price_per_book" class="form-label">Price Per Book</label>
                        <input type="number" step="0.01" class="form-control" id="price_per_book" wire:model="price_per_book">
                        @error('price_per_book') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-6">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" wire:model="quantity">
                        @error('quantity') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-6">
                        <label for="quantity_now" class="form-label">Quantity Now</label>
                        <input type="number" class="form-control" id="quantity_now" wire:model="quantity_now">
                        @error('quantity_now') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label for="categroeis" class="form-label">Categories</label>
                <div class="d-flex gap-4">
                    <!-- Input text for selected categories -->
                    <input type="text" class="form-control" id="selected_categories" wire:model="selectedCategories" readonly>
                    
                    <!-- Dropdown with multi-select checkboxes -->
                    <div class="dropdown">
                        <button class="btn bg-white shadow-sm rounded-3 dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Categories
                        </button>
                        <ul class="dropdown-menu px-2" aria-labelledby="categoryDropdown" style="max-height: 200px; overflow-y: auto;">
                            @foreach ($categories as $category)
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" wire:model.live="selectedDropdownCategories" value="{{ $category->id }}"> {{ $category->category_name }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @error('selectedCategories') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label for="bookshelves" class="form-label">Bookshelves</label>
                <div class="d-flex gap-4">
                    <!-- Input text for selected categories -->
                    <input type="text" class="form-control" id="selected_categories" wire:model="selectedBookshelves" readonly>
                    
                    <!-- Dropdown with multi-select checkboxes -->
                    <div class="dropdown">
                        <button class="btn bg-white shadow-sm rounded-3 dropdown-toggle" type="button" id="bookshelfDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Bookshelves
                        </button>
                        <ul class="dropdown-menu px-2" aria-labelledby="bookshelfDropdown" style="max-height: 200px; overflow-y: auto;">
                            @foreach ($bookshelves as $bookshelf)
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" wire:model.live="selectedDropdownBookshelves" value="{{ $bookshelf->id }}"> {{ $bookshelf->bookshelf_number }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @error('selectedCategories') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="5" wire:model="description"></textarea>
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="d-flex justify-content-between">
                        <a href="{{ route('books') }}" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // $(document).ready(function() {
    //     $('#image-preview').on('click', function() {
    //         $('#cover_image').click();
    //     });

    //     $('#cover_image').on('change', function(event) {
    //         const input = event.target;
    //         if (input.files && input.files[0]) {
    //             const reader = new FileReader();
    //             reader.onload = function(e) {
    //                 $('#image-preview').addClass('has-image');
    //                 $('#image-preview img').attr('src', e.target.result);
    //             };
    //             reader.readAsDataURL(input.files[0]);
    //         }
    //     });
    // });
</script>
@endpush

