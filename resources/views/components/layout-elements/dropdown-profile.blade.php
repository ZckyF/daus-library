<div class="dropdown dropdown-header">
    <button class=" btn dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="https://via.placeholder.com/40" alt="Avatar" class="avatar me-2 shadow-sm">
      <span class="me-2 fw-medium">{{ Auth::user()->username }}</span>
    
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
      <li><a class="dropdown-item" href="#">Profile</a></li>
      <li> <form action="{{ route('logout') }}" method="post">
        @csrf
          <button type="submit" class="dropdown-item">
            Logout
          </button>
      </form></li>
    </ul>
</div>