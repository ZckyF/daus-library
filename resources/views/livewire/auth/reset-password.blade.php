<div class="d-flex justify-content-center  ">
    <div class="text-align mt-5  px-4 py-4   w-50 ">
        <h1 class="mb-4  text-center ">Reset Password</h1>

        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form wire:submit="resetPassword" class="p-2  rounded">
            @csrf
            
            <div class="form-group mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" wire:model="form.password" class="form-control bg-transparent @error('form.password') is-invalid @enderror" id="password" placeholder="Enter your password">
                @error('form.password')
                <div id="password" class="invalid-feedback">
                    {{ $message }}
                </div> 
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="passwordConfrim" class="form-label">Password Confirmation</label>
                <input type="password" wire:model="form.password_confirmation" class="form-control bg-transparent @error('form.password_confirmation') is-invalid @enderror" id="passwordConfirm" placeholder="Enter your password Confirm">
                @error('form.password_confirmation')
                <div id="password" class="invalid-feedback">
                    {{ $message }}
                </div> 
                @enderror
            </div>

            
            <div class="d-grid gap-1">
                <button class="btn btn-primary" type="submit" >
                    <span class="text-white"> <strong>Submit</strong> </span>  
                </button>
            </div>
        </form>
    </div>
</div>
 

