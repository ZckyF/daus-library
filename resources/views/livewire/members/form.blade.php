<div class="row">
    <div class="mb-3 col-md-12">
        <label for="image" class="form-label py-5 image-avatar">

            <img id="image-preview" src="{{ $form->image_name ? (is_string($form->image_name) ? Storage::url('members/' . $form->image_name) : $form->image_name->temporaryUrl()) : "" }}" alt="Image" />

            <span class="icon-image">
                <i class="bi bi-plus add-image-icon"></i>
            </span>
            
        </label>
        <input type="file" class="form-control file-img" id="image"  accept="image/png, image/gif, image/jpeg" wire:model="form.image_name" style="display: none">
        @error('form.image_name') <span class="text-danger">{{ $message }}</span> @enderror
    
    </div>

    @if(isset($isEditPage) && $isEditPage)
        <div class="mb-3 col-md-4">
            <label for="number_card" class="form-label">Number Card</label>
            <input type="text" class="form-control" wire:model="form.number_card" id="number_card" disabled>
        </div>
    @endif
    <div class="mb-3 col-md-4">
        <label for="full_name" class="form-label">Full Name</label>
        <input type="text" class="form-control @error('form.full_name') is-invalid @enderror" id="full_name" wire:model="form.full_name">
        @error('form.full_name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="mb-3 col-md-4">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('form.email') is-invalid @enderror" id="email" wire:model="form.email">
        @error('form.email') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 {{ isset($isEditPage) && $isEditPage ? 'col-md-6' : 'col-md-4' }}">
        <label for="phone_number" class="form-label">Phone Number</label>
        <input type="string" class="form-control @error('form.phone_number') is-invalid @enderror" id="phone_number" wire:model="form.phone_number">
        @error('form.phone_number') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    @if(isset($isEditPage) && $isEditPage)
        <div class="mb-3 col-md-6">
            <label for="user" class="form-label">Last Added Or Edited By</label>
            <input type="text" class="form-control" value="{{ $user }}" disabled>
        </div>
    @endif
    <div class="mb-3 col-md-12">
        <label for="address" class="form-label">Address</label>
        <textarea type="address" class="form-control @error('form.address') is-invalid @enderror" id="address" wire:model="form.address" rows="5"></textarea>
        @error('form.address') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="d-flex justify-content-end gap-3 px-5">
        <a wire:navigate href="{{ route('members') }}" class="btn btn-outline-secondary shadow-sm">
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

    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal"> 
        Are you sure you want to delete this member?
    </x-notifications.modal>

    
</div>