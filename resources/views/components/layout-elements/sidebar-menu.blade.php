<aside id="sidebarMenu" class="col-md-4 col-lg-2 d-md-block sidebar collapse">
    <div class="position-sticky  sidebar-sticky">
      <div class="nav-header ms-3 mb-3">
        <a href="/" class="text-decoration-none">
          <img src="{{ asset('logo-dasbry.png') }}" alt="Company Logo" class="mb-2">
          <strong class="ms-1" style="font-size: 25px; letter-spacing: 4px">asbry</strong>
        </a>
      </div>
      <ul class="nav flex-column">
        <x-layout-elements.nav-link :active="request()->routeIs('dashboard')" href="/" icon="speedometer2"> Dashboard </x-layout-elements.nav-link>
      </ul>

      <hr class="mt-0">
      
      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-2 mb-3 ">
        <span>Master Data</span>
      </h6>
      <ul class="nav flex-column"> 
        <x-layout-elements.nav-link :active="request()->routeIs('books')" href="/books" icon="book"> Books </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('categories')" href="/categories" icon="journal"> Categories </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('members')" href="/members" icon="card-text"> Members </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('users')" href="/users" icon="person"> Users </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('employees')" href="/employees" icon="card-text"> Employees </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('roles')" href="/roles" icon="person-gear"> Roles </x-layout-elements.nav-link>
      </ul>
     
      <hr class="mt-0">

      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-3 ">
        <span>Transaction</span>
      </h6>
      <ul class="nav flex-column mb-2">
        <x-layout-elements.nav-link :active="request()->routeIs('borrow-books')" href="/borrow-books" icon="journals"> Borrow Books </x-layout-elements.nav-link>
        <x-layout-elements.nav-link :active="request()->routeIs('fines')" href="/fines" icon="cash"> Fines </x-layout-elements.nav-link>
      </ul>

      <hr class="mt-0">

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