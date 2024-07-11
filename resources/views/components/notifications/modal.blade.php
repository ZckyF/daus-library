@php
  $classModal = $classModal ?? 'modal fade';
  $buttonText = $buttonText ?? 'Yes';
@endphp

<div wire:ignore.self class="modal {{ $classModal }}" id="{{ $targetModal }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $title }}</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {{ $slot }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary text-white " wire:click="{{ $action }}">
            <span wire:loading wire:target="{{ $action }}" class="spinner-border spinner-border-sm me-1"></span>
            <span wire:loading.remove wire:target="{{ $action }}">{{ $buttonText }}</span>
            
        </button>
        </div>
      </div>
    </div>
</div>