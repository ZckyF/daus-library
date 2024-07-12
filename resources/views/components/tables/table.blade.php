

<table class="table {{ $tableClass }}"> 
    
    <thead>
        
        <tr>
            @foreach ($columns as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
       {{$slot}}
    </tbody>
</table>