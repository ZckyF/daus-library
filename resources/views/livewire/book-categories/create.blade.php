
<div class="mt-5">
    <h3 class="mb-4">Create New Category Book</h3>
    <form wire:submit.prevent="save">
        @csrf
        @include('livewire.book-categories.form')
    </form>
</div>