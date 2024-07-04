<header class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 mb-3">
    <h1 class="h1">
        {{ Request::segment(1) == '' ? 'Dashboard' : ucwords(str_replace('-', ' ', Request::segment(1))) }}
    </h1>

    <x-layout-elements.dropdown-profile />
    

  </header>