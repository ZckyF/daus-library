
<div class="d-flex justify-content-center  ">
    <div class="text-align mt-5 px-4 py-4 w-50">
        <h1 class="mb-1  text-center ">Login</h1>
        <p class="text-center mb-3 cl">Welcome to Dasbry </p>
        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
      
        <form wire:submit="login" class="p-2 rounded">
            @csrf
            <div class="form-group mb-3">
                <label for="usernameOrEmail" class="form-label">Username Or Email</label>
                <input 
                type="text" 
                wire:model="form.usernameOrEmail" 
                class="form-control bg-transparent @error('form.usernameOrEmail') is-invalid @enderror"  id="usernameOrEmail" 
                placeholder="Enter your username or email" />
                
                @error('form.usernameOrEmail')
                <div id="usernameOrEmail" class="invalid-feedback">
                    {{ $message }}
                </div> 
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" wire:model="form.password" class="form-control bg-transparent @error('form.password') is-invalid @enderror"" id="password" placeholder="Enter your password">
                @error('form.password')
                <div id="password" class="invalid-feedback">
                    {{ $message }}
                </div> 
                @enderror
            </div>

            <div class="mb-4">
                {{-- <a href="" style="" >Forgot password</a> --}}
                <a href="/forgot-password" wire:navigate style="color:rgb(45, 45, 235);" >Forgot password</a>
            </div>
            
            <div class="d-grid gap-1">
                <button class="btn btn-primary" type="submit" >
                    <span class="text-white"> <strong>Login</strong> </span>  
                </button>
                {{-- <x-buttons.button text="Login" type="submit" addClass="btn-primary" /> --}}
            </div>
        </form>
    </div>
</div>
