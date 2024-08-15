<div>
    <h3 class="mb-4">Change Password</h3>
    <form wire:submit.prevent="change">
        @csrf
        @if(session()->has('success'))
            <x-notifications.alert class="alert-success" :message="session('success')" />
        @endif
        <div class="row">
            <div class="mb-3 col-md-4">
                <label for="old_password" class="form-label">Old Password</label>
                <input type="password" class="form-control @error('form.old_password') is-invalid @enderror" id="old_password" wire:model="form.old_password" />
                @error('form.old_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label for="new_password" class="form-label">New Password</label>
                <input type="password" class="form-control @error('form.new_password') is-invalid @enderror" id="new_password" wire:model="form.new_password" />
                @error('form.new_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control @error('form.confirm_password') is-invalid @enderror" id="confirm_password" wire:model="form.confirm_password" />
                @error('form.confirm_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="d-flex justify-content-end gap-3 px-5">
            <a wire:navigate href="{{ route('settings') }}" class="btn btn-outline-secondary shadow-sm">
                <span class="me-1"><i class="bi bi-arrow-left"></i></span>
                <span>Back</span>
            </a> 
            <button type="submit" class="btn btn-primary text-white  shadow-sm">
                <span wire:loading wire:target="change" class="spinner-border spinner-border-sm me-1"></span>
                <span wire:loading.remove wire:target="change" class="me-1"><i class="bi bi-floppy"></i></span>
                <span>Change</span>
            </button>
        </div>
    </form>
</div>
