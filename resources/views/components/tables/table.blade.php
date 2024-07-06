@php
    $useCheckboxColumn = isset($useCheckboxColumn) && $useCheckboxColumn ? $useCheckboxColumn : false;
@endphp

<table class="table {{ $tableClass }}"> 
    
    <thead>
        
        <tr>
            @if ($useCheckboxColumn)
            <th>
                <input wire:loading.remove wire:target="toggleSelectAll" type="checkbox" class="form-check-input" wire:model="selectAllCheckbox" wire:click="toggleSelectAll">
                <span class="spinner-border text-primary spinner-border-sm" wire:loading wire:target="toggleSelectAll"></span>
            </th>
            @endif
            @foreach ($columns as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
       {{$slot}}
    </tbody>
</table>