
<div class="mt-5">
    <h3 class="mb-4">Edit Category Book</h3>
    <form wire:submit.prevent="save">
        @csrf
        @include('livewire.bookshelves.form')
    </form>
</div>