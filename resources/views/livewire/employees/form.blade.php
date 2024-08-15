@php
    if (isset($isEditPage) && $isEditPage) {
        $readonlyForm = Auth::user()->cannot('update', $form->employee) ? 'readonly' : '';
    } else {
        $readonlyForm = Auth::user()->cannot('create', App\Models\User::class) ? 'readonly' : '';
    }
@endphp

<div class="row mt-3">
    <div class="mb-3 col-md-6">
        <label for="full_name" class="form-label">Full Name</label>
         <input {{ $readonlyForm }} type="text" class="form-control @error('form.full_name') is-invalid @enderror" id="full_name" wire:model="form.full_name" >
        @error('form.full_name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="email" class="form-label">Email</label>
         <input {{ $readonlyForm }} type="email" class="form-control @error('form.email') is-invalid @enderror" id="email" wire:model="form.email" >
        @error('form.email') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="phone_number" class="form-label">Phone Number</label>
         <input {{ $readonlyForm }} type="text" class="form-control @error('form.phone_number') is-invalid @enderror" id="phone_number" wire:model="form.phone_number" >
        @error('form.phone_number') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="nik" class="form-label">NIK</label>
         <input {{ $readonlyForm }} type="text" class="form-control @error('form.nik') is-invalid @enderror" id="nik" wire:model="form.nik" >
        @error('form.nik') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    @if (isset($isEditPage) && $isEditPage)
        <div class="mb-3 col-md-12">
            <label for="user" class="form-label">Has User</label>
            <input  type="text" class="form-control" value="{{ $form->employee->user ? $form->employee->user->username : 'Don\'t Have' }}" disabled>
        </div>
    @endif
    <div class="mb-3 col-12">
        <label for="address" class="form-label">Address</label>
        <textarea {{ $readonlyForm }} class="form-control @error('form.address') is-invalid @enderror" id="address" rows="5" wire:model="form.address"></textarea>
        @error('form.address') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="d-flex justify-content-end gap-3 px-5">
        <a wire:navigate href="{{ route('employees') }}" class="btn btn-outline-secondary shadow-sm">
            <span class="me-1"><i class="bi bi-arrow-left"></i></span>
            <span>Back</span>
        </a>
        @if (isset($isEditPage) && $isEditPage && Auth::user()->can('delete', $form->employee))
            <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <span class="me-1"><i class="bi bi-trash"></i></span>
                <span>Delete</span>
            </button> 
        @endif
        
        @if(Auth::user()->can('update', $form->employee) || Auth::user()->can('create', App\Models\User::class))
            <button type="submit" class="btn btn-primary text-white  shadow-sm">
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                <span wire:loading.remove wire:target="save" class="me-1"><i class="bi bi-floppy"></i></span>
                <span>Save</span>
            </button>   
        @endcan
    </div>
    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal">
        Are you sure you want to delete this employee ?
    </x-notifications.modal>
</div>