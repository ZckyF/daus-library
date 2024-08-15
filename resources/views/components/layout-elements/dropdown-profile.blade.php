<div class="dropdown dropdown-header">
    <button class=" btn dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
    <img src="{{ Storage::url('avatars/' . Auth::user()->avatar_name) }}" alt="Avatar" class="avatar me-2 shadow-sm">
      <span class="me-2 fw-medium">{{ Auth::user()->username }}</span>
    
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
      <li><a wire:navigate class="dropdown-item" href="{{ route('settings.profile') }}">Profile</a></li>
      <li data-bs-toggle="modal" data-bs-target="#logoutModal"> 
          <button class="dropdown-item">
            Logout
          </button>
      </li>
    </ul>
</div>