@php
    $readonlyForm = Auth::user()->cannot('update', $form->bookCategory) ? 'readonly' : '';
@endphp

<div class="row mt-3">
    <div class="mb-3 {{ isset($isEditPage) && $isEditPage ? 'col-md-6' : 'col-md-12' }}">
        <label for="category_name" class="form-label">Category Name</label>
         <input {{ $readonlyForm }} type="text" class="form-control @error('form.category_name') is-invalid @enderror" id="category_name" wire:model="form.category_name" >
        @error('form.category_name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    @if (isset($isEditPage) && $isEditPage)
        <div class="mb-3 col-md-6">
            <label for="user" class="form-label">Last Added Or Edited By</label>
            <input type="text" class="form-control" value="{{ $user }}" disabled>
        </div>
    @endif
    <div class="mb-3 col-12">
        <label {{ $readonlyForm }} for="description" class="form-label">Description</label>
        <textarea class="form-control @error('form.description') is-invalid @enderror" id="description" rows="5" wire:model="form.description"></textarea>
        @error('form.description') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="d-flex justify-content-end gap-3 px-5">
        <a wire:navigate href="{{ route('book-categories') }}" class="btn btn-outline-secondary shadow-sm">
            <span class="me-1"><i class="bi bi-arrow-left"></i></span>
            <span>Back</span>
        </a>
        @if ((isset($isEditPage) && $isEditPage) && Auth::user()->can('delete', $form->bookCategory))
            <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <span class="me-1"><i class="bi bi-trash"></i></span>
                <span>Delete</span>
            </button> 
        @endif
        @if (Auth::user()->can('update', $form->bookCategory) || Auth::user()->can('create', App\Models\BookCategory::class))   
            <button type="submit" class="btn btn-primary text-white  shadow-sm">
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                <span wire:loading.remove wire:target="save" class="me-1"><i class="bi bi-floppy"></i></span>
                <span>Save</span>
            </button>
        @endif
        
    </div>
    <x-notifications.modal title="Delete Confirmation" message="Are you sure you want to delete this book category ?" buttonText="Yes" action="delete" targetModal="deleteModal" />
</div>