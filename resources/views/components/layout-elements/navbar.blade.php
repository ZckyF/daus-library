
<style>
  @media (min-width: 767.98px) {  /* Targets screens larger than or equal to lg  */
  .navbar {
    display: none !important;
    
  }
}

.navbar {
  background-color: white !important;
  box-shadow: inset 0px -2px 0 rgba(0, 0, 0, .1);
}
</style>
<nav class="navbar navbar-light fixed-top">
  <div class="container-fluid">
    <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list " style="font-size: 1.5rem"></i>
    </button>
    <a class="navbar-brand" href="#">
      <img src="{{ asset('logo-dasbry.png') }}" alt="Logo" class="d-inline-block align-text-top">
    </a>

    <a class="navbar-brand" href="#">Navbar</a>
  </div>
</nav>