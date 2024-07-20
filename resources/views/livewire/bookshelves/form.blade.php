@php
    if (isset($isEditPage) && $isEditPage) {
        $readonlyForm = Auth::user()->cannot('update', $form->bookshelf) ? 'readonly' : '';
    } else {
        $readonlyForm = Auth::user()->cannot('create', App\Models\Bookshelf::class) ? 'readonly' : '';
    }
@endphp

<div class="row mt-3">
    <div class="mb-3 {{ isset($isEditPage) && $isEditPage ? 'col-md-6' : 'col-md-12' }}">
        <label for="bookshelf_number" class="form-label">Bookshelf Number</label>
         <input {{ $readonlyForm }} type="text" class="form-control @error('form.bookshelf_number') is-invalid @enderror" id="bookshelf_number" wire:model="form.bookshelf_number" >
        @error('form.bookshelf_number') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    @if (isset($isEditPage) && $isEditPage)
        <div class="mb-3 col-md-6">
            <label for="user" class="form-label">Last Added Or Edited By</label>
            <input  type="text" class="form-control" value="{{ $user }}" disabled>
        </div>
    @endif
    <div class="mb-3 col-12">
        <label for="location" class="form-label">Location</label>
        <textarea {{ $readonlyForm }} class="form-control @error('form.location') is-invalid @enderror" id="location" rows="5" wire:model="form.location"></textarea>
        @error('form.location') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="d-flex justify-content-end gap-3 px-5">
        <a wire:navigate href="{{ route('bookshelves') }}" class="btn btn-outline-secondary shadow-sm">
            <span class="me-1"><i class="bi bi-arrow-left"></i></span>
            <span>Back</span>
        </a>
        @if (isset($isEditPage) && $isEditPage && Auth::user()->can('delete', $form->bookshelf))
            <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <span class="me-1"><i class="bi bi-trash"></i></span>
                <span>Delete</span>
            </button> 
        @endif
        
        @if(Auth::user()->can('update', $form->bookshelf) || Auth::user()->can('create', App\Models\Bookshelf::class))
            <button type="submit" class="btn btn-primary text-white  shadow-sm">
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                <span wire:loading.remove wire:target="save" class="me-1"><i class="bi bi-floppy"></i></span>
                <span>Save</span>
            </button>   
        @endcan
    </div>
    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal">
        Are you sure you want to delete this book bookshelf ?
    </x-notifications.modal>
</div>