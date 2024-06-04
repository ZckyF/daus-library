<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? config( 'app.name') }}</title>
    <link rel="icon" href="{{ asset('logo-dasbry.png') }}">
</head>

<style lang="scss">
    .sidebar {
      position: fixed;
      top: 0;
      /* rtl:raw:
      right: 0;
      */
      bottom: 0;
      /* rtl:remove */
      left: 0;
      z-index: 100; /* Behind the navbar */
      padding: 30px 0 0; /* Height of navbar */
      box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
    }

    @media (max-width: 767.98px) {
      .sidebar {
        top: 5rem;
      }
    }



    .sidebar-sticky {
      height: calc(100vh - 48px);
      overflow-x: hidden;
      overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
      
    }

        /* Custom scrollbar for the sidebar */
    .sidebar-sticky::-webkit-scrollbar {
      width: 3px; /* width of the scrollbar */
    }

    .sidebar-sticky::-webkit-scrollbar-thumb:hover {
      background: #555; /* color of the thumb when hovered */
    }

    .sidebar .nav-link {
      font-weight: 500;
      color: #838383;
      margin-bottom: 15px;
    }

    .sidebar .nav-link:hover {
      color: #424242 !important;
    }

    .sidebar .bi {
        background-color: #EAEAEA;
        padding: 7px;
        border-radius: 10px;
        color: #838383;
    }
    .sidebar .bi.active {
        background-color: #9370db;
        color: #ffffff;
    }

    /* .sidebar .nav-link .feather {
      margin-right: 4px;
      color: #727272;
    } */

    .sidebar .nav-link.active {
      color: #424242;
      font-weight: bold;
    }

    /* .sidebar .nav-link:hover .feather,
    .sidebar .nav-link.active .feather {
      color: inherit;
    } */

    .sidebar-heading {
      font-size: 1rem;
      color: #979797;
    }

</style>
@vite(['resources/js/app.js', 'resources/sass/app.scss'])
<body>
    

  <x-layout-elements.navbar />

    <div class="container-fluid">
        <div class="row">
          <x-layout-elements.sidebar-menu />
        </div>
        <main class="col-md-8 ms-sm-auto col-lg-10 px-md-4">
            {{ $slot }}
        </main>

       
    </div>

    
</body>
</html>