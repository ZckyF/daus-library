<div style="height: 10000px">
    <h1>Dashboard</h1>
    <form action="{{ route('logout') }}" method="post">
      @csrf
        <button type="submit" class="btn btn-primary">
          Log out
        </button>
    </form>
</div>