<div>
   
        <h1 class="mb-4  text-center ">Reset Password</h1>

        @if (session()->has('error'))
            <x-notifications.alert class="alert-danger" :message="session('error')" />
        @endif

        <form wire:submit="resetPassword" class="p-2  rounded">
            @csrf
            
            <div class="form-group mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" wire:model="form.password" class="form-control @error('form.password') is-invalid @enderror" id="password" placeholder="Enter your password">
                @error('form.password')
                <div id="password" class="invalid-feedback">
                    {{ $message }}
                </div> 
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="passwordConfrim" class="form-label">Password Confirmation</label>
                <input type="password" wire:model="form.password_confirmation" class="form-control @error('form.password_confirmation') is-invalid @enderror" id="passwordConfirm" placeholder="Enter your password Confirm">
                @error('form.password_confirmation')
                <div id="password" class="invalid-feedback">
                    {{ $message }}
                </div> 
                @enderror
            </div>

            
            <div class="d-grid gap-1">
                <button class="btn btn-primary text-white fw-bold shadow-sm" type="submit">
                    <span wire:loading wire:target="resetPassword" class="spinner-border spinner-border-sm me-1"></span>
                    <span wire:loading.remove wire:target="resetPassword">Submit</span>
                </button>
            </div>
        </form>
    
</div>
 

