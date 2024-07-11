@props(['active' => false,'icon' => ''])

@php
    $classes = ($active ?? false) ? 'nav-link active' : 'nav-link';
@endphp

<li class="nav-item">
    <a wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
      <i class="bi bi-{{ $icon }} me-1 {{ $active === true ? 'active' : ''}}"></i>
      {{ $slot }}
    </a>
  </li>