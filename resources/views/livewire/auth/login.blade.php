
<div>
        <h1 class="mb-1 text-center">Login</h1>
        <p class="text-center mb-3">Welcome to Dasbry</p>

        @if (session()->has('success'))
            <x-notifications.alert class="alert-success" :message="session('success')" />
        @endif

        @if (session()->has('error'))
            <x-notifications.alert class="alert-danger" :message="session('error')" />
        @endif

        <form wire:submit.prevent="login" class="p-2 rounded">
        @csrf

        <div class="form-group mb-3">
            <label for="usernameOrEmail" class="form-label text-center">Username Or Email</label>
            <input type="text" wire:model="form.usernameOrEmail" class="form-control @error('form.usernameOrEmail') is-invalid @enderror" id="usernameOrEmail" placeholder="Enter your username or email" />

            @error('form.usernameOrEmail')
            <div id="usernameOrEmail" class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="password" class="form-label text-center">Password</label>
            <input type="password" wire:model="form.password" class="form-control @error('form.password') is-invalid @enderror" id="password" placeholder="Enter your password">

            @error('form.password')
            <div id="password" class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="mb-4">
            <a href="/forgot-password" wire:navigate style="color:rgb(45, 45, 235);">Forgot password</a>
        </div>

        <div class="d-grid ">
            <button class="btn btn-primary text-white fw-bold shadow-sm" type="submit">
                <span wire:loading wire:target="login" class="spinner-border spinner-border-sm me-1"></span>
                <span wire:loading.remove wire:target="login">Login</span>
            </button>
        </div>
        </form>
</div>

