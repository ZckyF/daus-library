  
  <style>

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
      background-color: white;
      display: block !important;
      transition: transform 0.3s ease; 
    }

    @media (max-width: 767.98px) {
      .sidebar {
        background-color: white;
        transform: translateX(-100%);
        transition: transform 0.3s ease, display 0s 0.3s; /* Transisi untuk animasi */
        /* margin-top: 4rem; */
      }

      .sidebar-active {
        transform: translateX(0);
        transition: transform 0.3s ease; /* Transisi untuk animasi */
        z-index: 9999;
      }


    }

    

    .sidebar-sticky {
      height: calc(100vh - 48px);
      overflow-x: hidden;
      overflow-y: auto; 
      scrollbar-color: rgba(0, 0, 0, 0.3) #e0e0e0; 
      scrollbar-width: none;
    }

        /* Custom scrollbar for the sidebar */
    .sidebar-sticky::-webkit-scrollbar {
      width: 4px; /* width of the scrollbar */
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
        transition:  .3s ease; 
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
<aside id="sidebarMenu" class="col-md-3 col-5 col-lg-2 sidebar collapse">
    <div class="position-sticky  sidebar-sticky">
      <div class="nav-header ms-3 mb-3">
        <a href="/" class="text-decoration-none">
          <img src="{{ asset('logo-dasbry.png') }}" alt="Company Logo" class="mb-2">
          <strong class="ms-1" style="font-size: 25px; letter-spacing: 4px">asbry</strong>
        </a>
      </div>
      <ul class="nav flex-column border-bottom">
        <x-layout-elements.nav-link :active="request()->routeIs('dashboard')" href="/" icon="speedometer2"> Dashboard </x-layout-elements.nav-link>
      </ul>

      
      
      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-3 ">
        <span>Master Data</span>
      </h6>
      <ul class="nav flex-column border-bottom"> 
        <x-layout-elements.nav-link :active="request()->routeIs('books')" href="/books" icon="book"> Books </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('categories')" href="/categories" icon="tags"> Categories </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('members')" href="/members" icon="person-vcard"> Members </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('users')" href="/users" icon="person"> Users </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('employees')" href="/employees" icon="people"> Employees </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('roles')" href="/roles" icon="person-gear"> Roles </x-layout-elements.nav-link>
      </ul>
     

      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-3 ">
        <span>Transaction</span>
      </h6>
      <ul class="nav flex-column mb-2 border-bottom">
        <x-layout-elements.nav-link :active="request()->routeIs('borrow-books')" href="/borrow-books" icon="journals"> Borrow Books </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('fines')" href="/fines" icon="cash"> Fines </x-layout-elements.nav-link>
      </ul>


      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-3 ">
        <span>Other Option</span>
      </h6>
      <ul class="nav flex-column mb-2">
        <x-layout-elements.nav-link :active="request()->routeIs('settings')" href="/settings" icon="gear"> Settings </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('notification')" href="/notification" icon="bell"> Notification </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('logout')" href="/logout" icon="box-arrow-left"> Logout </x-layout-elements.nav-link>
      </ul>
    </div>
</aside>