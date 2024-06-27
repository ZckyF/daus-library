<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? config( 'app.name') }}</title>
    <link rel="icon" href="{{ asset('logo-dasbry.png') }}">
</head>

<style>
  @media (max-width: 992px) { 
    /* .container-fluid .row-active {
        background-color: rgba(0, 0, 0, 0.8);
        position: absolute;
        height: 100%;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        transition: background-color 0.3s ease; 
        z-index: 9999;
    } */
    .row main {
      margin-top: 60px;
      
    }
    header .dropdown-header {
      display: none;
    }
  }
  .avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
  }

  footer {
      position: sticky;
      bottom: 0;
  }

</style>
@stack('styles')
@vite(['resources/js/app.js', 'resources/sass/app.scss'])
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<body>
    
  <x-layout-elements.navbar />
    <div class="container-fluid">
        <div class="row">
          <x-layout-elements.sidebar-menu />

          <main class="col-md-auto ms-sm-auto col-lg-10 px-md-4">
            <x-layout-elements.header />
            <hr>
            {{ $slot }}
          </main>
          
          <x-layout-elements.footer />
        </div>
    </div>

    
    <script>
      $(document).ready(function() {
        const $mySidebar = $('.sidebar');
        const $navbarBtn = $('.navbar-toggler');
        const $row = $('.container-fluid .row');
    
        $navbarBtn.on('click', function() {
          $mySidebar.toggleClass('sidebar-active');
          $row.toggleClass('row-active');
        });
    
        $(document).on('click', function(event) {
          if (!$mySidebar.is(event.target) && $mySidebar.has(event.target).length === 0 &&
              !$navbarBtn.is(event.target) && $navbarBtn.has(event.target).length === 0) {
            if ($row.hasClass('row-active')) {
              $row.removeClass('row-active');
              $mySidebar.removeClass('sidebar-active');
            }
          }
        });
      });
    </script>
    @stack('scripts')
    
    
</body>
</html>