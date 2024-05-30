
<div class="d-flex justify-content-center  ">
    <div class="text-align mt-5  px-4 py-4   w-50 ">
        <h1 class="mb-1  text-center ">Login</h1>
        <p class="text-center mb-3 cl">Welcome to Dasbry </p>
        <form action="" class="p-2  rounded">
            @csrf
            <!-- Tambahkan field form di sini -->
            <div class="form-group mb-3">
                <label for="usernameOrEmail" class="form-label">Username Or Email</label>
                <input type="text" class="form-control bg-transparent" id="usernameOrEmail" placeholder="Enter your username or email">
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control bg-transparent" id="password" placeholder="Enter your password">
            </div>

            <div class="mb-4">
                {{-- <a href="" style="" >Forgot password</a> --}}
                <a href="/forgot-password" wire:navigate style="color:rgb(45, 45, 235);" >Forgot password</a>
            </div>
            
            <div class="d-grid gap-1">
                <x-buttons.button text="Login" type="submit" addClass="btn-primary" />
              </div>
        </form>
    </div>
</div>
