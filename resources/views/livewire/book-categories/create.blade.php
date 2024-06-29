
<div class="mt-5">
    <h3 class="mb-4">Create New Book</h3>
    @if (session()->has('success'))
        <x-notifications.alert class="alert-success" :message="session('success')" />
    @endif
    <form wire:submit.prevent="save">
        @csrf
        @include('livewire.book-categories.form')
    </form>
</div>