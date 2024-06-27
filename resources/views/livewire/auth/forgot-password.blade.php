<div>

        <h1 class="mb-4  text-center ">Forgot Password</h1>

        @if (session()->has('success'))
            <x-notifications.alert class="alert-success" :message="session('success')" />
        @endif

        @if (session()->has('error'))
            <x-notifications.alert class="alert-danger" :message="session('error')" />
        @endif

        <form wire:submit="forgotPassword" class="p-2  rounded">
            @csrf
            <!-- Tambahkan field form di sini -->
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" wire:model="form.email" class="form-control @error('form.email') is-invalid @enderror" id="email" placeholder="Enter your email">
                @error('form.email')
                <div id="email" class="invalid-feedback">
                    {{ $message }}
                </div> 
                @enderror
            </div>

            <div class="mb-4">
                {{-- <a href="" style="" >Forgot password</a> --}}
                <a href="/login" wire:navigate style="color:rgb(45, 45, 235);" >Back to Login</a>
            </div>
            
            <div class="d-grid gap-1">
                <button class="btn btn-primary text-white fw-bold shadow-sm" type="submit">
                    <span wire:loading wire:target="forgotPassword" class="spinner-border spinner-border-sm me-1"></span>
                    <span wire:loading.remove wire:target="forgotPassword">Send</span>
                </button>
            </div>
        </form>
    
</div>
 

