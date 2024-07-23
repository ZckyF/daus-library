<div class="mt-5">
    <h3 class="mb-4">Edit Employee</h3>
    <form wire:submit.prevent="save">
        @csrf
        @include('livewire.employees.form')
    </form>
</div>