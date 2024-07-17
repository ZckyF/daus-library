  
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

    @media (max-width: 992px) {
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
    scrollbar-color: #b8b8b8 #e0e0e0; 
    scrollbar-width: thin;
    padding-bottom: 4rem;
  }

  /* Custom scrollbar for the sidebar */
  .sidebar-sticky::-webkit-scrollbar {
    width: 6px; 
  }

  .sidebar-sticky::-webkit-scrollbar-thumb {
    background: #b8b8b8; 
    border-radius: 4px; 
  }


  .sidebar-sticky::-webkit-scrollbar-track {
    background: #f0f0f0; 
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
<aside id="sidebarMenu" class="col-md-4 col-6 col-lg-2 sidebar collapse">
    <div class="nav-header ms-3 mb-4">
      <a href="/" class="text-decoration-none">
        <img src="{{ asset('logo-dasbry.png') }}" alt="Company Logo" class="mb-2">
        <strong class="ms-1" style="font-size: 25px; letter-spacing: 4px">asbry</strong>
      </a>
    </div>
    <div class="position-sticky sidebar-sticky">
      
      <ul class="nav flex-column border-bottom">
        <x-layout-elements.side-link model="dashboard" :active="request()->routeIs('dashboard')" href="/" icon="speedometer2"> Dashboard </x-layout-elements.side-link>
      </ul>

      
      
      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-3 ">
        <span>Master Data</span>
      </h6>
      <ul class="nav flex-column border-bottom"> 
        <x-layout-elements.side-link :model="App\Models\Book::class" :active="request()->routeIs('books*') && !request()->routeIs('bookshelves*')" href="/books" icon="book"> Books </x-layout-elements.side-link>
        <x-layout-elements.side-link :model="App\Models\BookCategory::class" :active="request()->routeIs('book-categories*')" href="/book-categories" icon="tags"> Book Categories </x-layout-elements.side-link>
        <x-layout-elements.side-link :model="App\Models\bookshelf::class" :active="request()->routeIs('bookshelves*')" href="/bookshelves" icon="bookshelf"> Bookshelves </x-layout-elements.side-link>
        <x-layout-elements.side-link :model="App\Models\Member::class" :active="request()->routeIs('members*')" href="/members" icon="person-vcard"> Members </x-layout-elements.side-link>
        <x-layout-elements.side-link :model="App\Models\User::class" :active="request()->routeIs('users*')" href="/users" icon="person"> Users </x-layout-elements.side-link>
        <x-layout-elements.side-link :model="App\Models\Employee::class" :active="request()->routeIs('employees*')" href="/employees" icon="people"> Employees </x-layout-elements.side-link>
        <x-layout-elements.side-link :model="App\Models\Employee::class" :active="request()->routeIs('roles*')" href="/roles" icon="person-gear"> Roles </x-layout-elements.side-link>
      </ul>
     
   
      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-3 ">
        <span>Transaction</span>
      </h6>
      <ul class="nav flex-column mb-2 border-bottom">
        <x-layout-elements.side-link :model="App\Models\Fine::class" :active="request()->routeIs('carts*')" href="/carts" icon="cart"> Carts </x-layout-elements.side-link>
        <x-layout-elements.side-link :model="App\Models\BorrowBook::class" :active="request()->routeIs('borrow-books*')" href="/borrow-books" icon="journals"> Borrow Books </x-layout-elements.side-link>
        <x-layout-elements.side-link :model="App\Models\Fine::class" :active="request()->routeIs('fines*')" href="/fines" icon="cash"> Fines </x-layout-elements.side-link>
      </ul>  
     
      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-3 ">
        <span>Other Option</span>
      </h6>
      <ul class="nav flex-column mb-2">
        <x-layout-elements.side-link :model="App\Models\Book::class" :active="request()->routeIs('settings*')" href="/settings" icon="gear"> Settings </x-layout-elements.side-link>
        <x-layout-elements.side-link :model="App\Models\Book::class" :active="request()->routeIs('notification*')" href="/notification" icon="bell"> Notification </x-layout-elements.side-link>
        <x-layout-elements.side-link :model="App\Models\Book::class" :active="request()->routeIs('logout*')" href="/logout" icon="box-arrow-left"> Logout </x-layout-elements.side-link>
      </ul>
    </div>
</aside>