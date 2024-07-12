<div class="row">
    <div class="mb-3 col-12">
        <label for="cover_image" class="form-label py-5 image-avatar">

            <img id="cover-preview" src="{{ $form->cover_image_name ? (is_string($form->cover_image_name) ? Storage::url('covers/' . $form->cover_image_name) : $form->cover_image_name->temporaryUrl()) : "" }}" alt="Cover Image" />

            <span class="icon-image">
                <i class="bi bi-plus add-image-icon"></i>
            </span>
            
            
        </label>
        <input type="file" class="form-control file-img" id="cover_image"  accept="image/png, image/gif, image/jpeg" wire:model="form.cover_image_name" style="display: none">
        @error('form.cover_image_name') <span class="text-danger">{{ $message }}</span> @enderror
    
    </div>
      
    <div class="mb-3 col-md-4">
        <label for="isbn" class="form-label">ISBN</label>
         <input type="text" class="form-control @error('form.isbn') is-invalid @enderror" id="isbn" wire:model="form.isbn" >
        @error('form.isbn') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-4">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control @error('form.title') is-invalid @enderror" id="title" wire:model="form.title">
        @error('form.title') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-4">
        <label for="author" class="form-label">Author</label>
        <input type="text" class="form-control @error('form.author') is-invalid @enderror" id="author" wire:model="form.author">
        @error('form.author') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="published_year" class="form-label">Published Year</label>
        <input type="number" class="form-control @error('form.published_year') is-invalid @enderror" id="published_year" wire:model="form.published_year">
        @error('form.published_year') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="price_per_book" class="form-label">Price Per Book</label>
        <input type="number" step="0.01" class="form-control @error('form.price_per_book') is-invalid @enderror" id="price_per_book" wire:model="form.price_per_book">
        @error('form.price_per_book') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" class="form-control @error('form.quantity') is-invalid @enderror" id="quantity" wire:model="form.quantity">
        @error('form.quantity') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="quantity_now" class="form-label">Quantity Now</label>
        <input type="number" class="form-control @error('form.quantity_now') is-invalid @enderror" id="quantity_now" wire:model="form.quantity_now">
        @error('form.quantity_now') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="categories" data-bs-toggle="modal" data-bs-target="#categoryModal" class="form-label">Categories</label>
        <div class="d-flex align-items-center  gap-4">
            <input type="text" class="form-control @error('form.selectedCategories') is-invalid @enderror" id="selected_categories" wire:model="form.selectedCategories" readonly>

            <button type="button" class="btn bg-white shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#categoryModal" style="white-space: nowrap">
                Select Categories
            </button>
        </div>
       
        @error('form.selectedCategories') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="bookshelves" data-bs-toggle="modal" data-bs-target="#bookshelfModal" class="form-label">bookshelves</label> 
        <div class="d-flex align-items-center  gap-4">
            <input type="text" class="form-control @error('form.selectedBookshelves') is-invalid @enderror" id="selected_bookshelves" wire:model="form.selectedBookshelves" readonly />
            
            <button type="button" class="btn bg-white shadow-sm rounded-3" data-bs-toggle="modal" data-bs-target="#bookshelfModal" style="white-space: nowrap">
                Select Bookshelves
            </button>
        </div>
    
        @error('form.selectedBookshelves') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    @if(isset($isEditPage) && $isEditPage)
    <div class="mb-3 col-12">
        <label for="user" class="form-label">Last Added Or Edited By</label>
        <input type="text" class="form-control" value="{{ $user }}" disabled>
    </div>
    @endif
    <div class="mb-3 col-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control @error('form.description') is-invalid @enderror" id="description" rows="5" wire:model="form.description"></textarea>
        @error('form.description') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="d-flex justify-content-end gap-3 px-5">
        <a wire:navigate href="{{ route('books') }}" class="btn btn-outline-secondary shadow-sm">
            <span class="me-1"><i class="bi bi-arrow-left"></i></span>
            <span>Back</span>
        </a>
        @if (isset($isEditPage) && $isEditPage)
            <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <span class="me-1"><i class="bi bi-trash"></i></span>
                <span>Delete</span>
            </button> 
        @endif
        
        <button type="submit" class="btn btn-primary text-white  shadow-sm">
            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
            <span wire:loading.remove wire:target="save" class="me-1"><i class="bi bi-floppy"></i></span>
            <span>Save</span>
        </button>
    </div>
    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal">Are you sure you want to delete this book ? </x-notifications.modal>

    <x-notifications.modal title="Select Categories" action="selectCategories" targetModal="categoryModal" buttonText="Select">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search categories" wire:model.live.debounce.500ms="searchSelectCategories">
        </div>
        <div style="max-height: 400px; overflow-y: auto;">
            @foreach ($categories as $category)
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" wire:model="selectedModalCategories" value="{{ $category->id }}" id="category-{{ $category->id }}">
                    <label class="form-check-label" for="category-{{ $category->id }}">{{ $category->category_name }}</label>
                </div>
            @endforeach
        </div>    
    </x-notifications.modal>

    <x-notifications.modal title="Select Bookshelves" action="selectBookshelves" targetModal="bookshelfModal" buttonText="Select">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search bookshelves" wire:model.live.debounce.500ms="searchSelectBookshelves">
        </div>
        <div style="max-height: 400px; overflow-y: auto;">
            @foreach ($bookshelves as $bookshelf)
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" wire:model="selectedModalBookshelves" value="{{ $bookshelf->id }}" id="bookshelf-{{ $bookshelf->id }}">
                    <label class="form-check-label" for="bookshelf-{{ $bookshelf->id }}">{{ $bookshelf->bookshelf_number }}</label>
                </div>
            @endforeach
        </div>    
    </x-notifications.modal>

        {{-- <div class="d-flex gap-4">
            <!-- Input text for selected categories -->
            <input type="text" class="form-control @error('form.selectedCategories') is-invalid @enderror" id="selected_categories" wire:model="form.selectedCategories" readonly>
            
            <!-- Dropdown with multi-select checkboxes -->
            <div class="dropdown">
                <button class="btn bg-white shadow-sm rounded-3 dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Select Categories
                </button>
                <ul class="dropdown-menu px-2" aria-labelledby="categoryDropdown" style="max-height: 200px; overflow-y: auto;" wire:ignore>
                    @foreach ($categories as $category)
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" wire:model.live="selectedDropdownCategories" value="{{ $category->id }}"> {{ $category->category_name }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div> --}}
</div>

