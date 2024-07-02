@php
    $useCheckboxColumn = isset($useCheckboxColumn) && $useCheckboxColumn ? $useCheckboxColumn : false;
@endphp

<table class="table {{ $tableClass }}"> 
    
    <thead>
        
        <tr>
            @if ($useCheckboxColumn)
            <th>
                <input type="checkbox" class="form-check-input" wire:model="selectAllCheckbox" wire:click="toggleSelectAll">
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