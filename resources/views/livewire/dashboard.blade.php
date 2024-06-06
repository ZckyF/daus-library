<div >
    
    <form action="{{ route('logout') }}" method="post">
      @csrf
        <button type="submit" class="btn btn-primary">
          Log out
        </button>
    </form>
</div>