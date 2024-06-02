<div class="d-flex justify-content-center  ">
    <div class="text-align mt-5  px-4 py-4   w-50 ">
        <h1 class="mb-4  text-center ">Forgot Password</h1>

        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form wire:submit="forgotPassword" class="p-2  rounded">
            @csrf
            <!-- Tambahkan field form di sini -->
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" wire:model="form.email" class="form-control bg-transparent @error('form.email') is-invalid @enderror" id="email" placeholder="Enter your email">
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
                <button class="btn btn-primary" type="submit" >
                    <span class="text-white"> <strong>Send</strong> </span>  
                </button>
            </div>
        </form>
    </div>
</div>
 

