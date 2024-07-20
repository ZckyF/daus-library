@php
    if (isset($isEditPage) && $isEditPage) {
        $readonlyForm = Auth::user()->cannot('update', $form->user) ? 'readonly' : '';
    } else {
        $readonlyForm = Auth::user()->cannot('create', App\Models\User::class) ? 'readonly' : '';
    }
    $roles = Auth::user()->hasRole('super_admin') ? $roles : $roles->reject(fn($role) => $role->name == 'admin')->values();
    
@endphp

<div class="row">
    <div class="mb-3 col-12">
        <label for="cover_image" class="form-label py-5 image-avatar">

            <img id="cover-preview" src="{{ $form->avatar_name ? (is_string($form->avatar_name) ? Storage::url('avatars/' . $form->avatar_name) : $form->avatar_name->temporaryUrl()) : "" }}" alt="Cover Image" />

            <span class="icon-image">
                <i class="bi bi-plus add-image-icon"></i>
            </span>
            
            
        </label>
        <input {{ $readonlyForm == 'readonly' ? 'disabled' : '' }} type="file" class="form-control file-img" id="cover_image"  accept="image/png, image/gif, image/jpeg" wire:model="form.avatar_name" style="display: none">
        @error('form.avatar_name') <span class="text-danger">{{ $message }}</span> @enderror
    
    </div>
      
    <div class="mb-3 col-md-6">
        <label for="username" class="form-label">Username</label>
         <input {{ $readonlyForm }} type="text" class="form-control @error('form.username') is-invalid @enderror" id="username" wire:model="form.username" >
        @error('form.username') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="password" class="form-label">Password</label>
        <input {{ $readonlyForm }} type="password" class="form-control @error('form.password') is-invalid @enderror" id="password" wire:model="form.password">
        @error('form.password') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    
    <div class="mb-3 col-md-6">
        <label for="employee_id" class="form-label">Employee</label>
        <select id="employee_id" class="form-select @error('form.employee_id') is-invalid @enderror" wire:model="form.employee_id">
            <option value="">Select Employee</option>
            @foreach ($employees as $employee)
            <option value="{{ $employee->id }}" 
                {{ $employee->user ? 'disabled' : '' }}>
                {{ $employee->full_name }}
            </option>
        @endforeach
        </select>
        @error('form.employee_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="mb-3 col-md-6">
        <label for="role_id" class="form-label">Role</label>
        <select id="role_id" class="form-select @error('form.role_id') is-invalid @enderror" wire:model="form.role_id">
            <option value="">Select Role</option>
            @foreach ($roles as $role)
            <option value="{{ $role->id }}">
                {{ $role->name }}
            </option>
        @endforeach
        </select>
        @error('form.role_id') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    @if(isset($isEditPage) && $isEditPage)
        <div class="mb-3 col-6">
            <label for="user" class="form-label">Last Added Or Edited By</label>
            <input type="text" class="form-control" value="{{ $user }}" disabled>
        </div>
    @endif
    <div class="d-flex justify-content-end gap-3 px-5">
        <a wire:navigate href="{{ route('users') }}" class="btn btn-outline-secondary shadow-sm">
            <span class="me-1"><i class="bi bi-arrow-left"></i></span>
            <span>Back</span>
        </a>
        @if ((isset($isEditPage) && $isEditPage) && Auth::user()->can('delete', $form->user))
            <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <span class="me-1"><i class="bi bi-trash"></i></span>
                <span>Delete</span>
            </button> 
        @endif
        @if (Auth::user()->can('update', $form->user) || Auth::user()->can('create', App\Models\User::class))
            <button type="submit" class="btn btn-primary text-white shadow-sm">
                <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                <span wire:loading.remove wire:target="save" class="me-1"><i class="bi bi-floppy"></i></span>
                <span>Save</span>
            </button> 
        @endif
    
        
    </div>
    <x-notifications.modal title="Delete Confirmation" action="delete" targetModal="deleteModal">Are you sure you want to delete this book ? </x-notifications.modal>


</div>

