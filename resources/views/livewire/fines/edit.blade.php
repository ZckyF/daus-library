
<div class="mt-5">
    <h3 class="mb-4">Edit Fines</h3>
    <form wire:submit.prevent="save">
        @csrf
        @include('livewire.fines.form')
    </form>
</div>