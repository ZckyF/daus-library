<div class="d-flex justify-content-center  ">
    <div class="text-align mt-5  px-4 py-4   w-50 ">
        <h1 class="mb-4  text-center ">Forgot Password</h1>
        <form action="" class="p-2  rounded">
            @csrf
            <!-- Tambahkan field form di sini -->
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control bg-transparent" id="email" placeholder="Enter your email">
            </div>

            <div class="mb-4">
                {{-- <a href="" style="" >Forgot password</a> --}}
                <a href="/login" wire:navigate style="color:rgb(45, 45, 235);" >Back to Login</a>
            </div>
            
            <div class="d-grid gap-1">
                <x-buttons.button text="Submit" type="submit" addClass="btn-primary" />
              </div>
        </form>
    </div>
</div>
 

