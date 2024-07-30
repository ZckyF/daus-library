<div class="mt-5">
    <h3 class="mb-4">Edit Borrow Book</h3>
    <form wire:submit.prevent="save">
        @csrf
        @include('livewire.borrow-books.form')
    </form>
</div>