
<div class="mt-5">
    <h3 class="mb-4">Create New Bookshelf</h3>
    <form wire:submit.prevent="save">
        @csrf
        @include('livewire.bookshelves.form')
    </form>
</div>