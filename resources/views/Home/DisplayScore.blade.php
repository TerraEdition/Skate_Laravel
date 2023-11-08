@foreach ($participant as $t)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $t->no_participant }}</td>
        <td>{{ ucwords($t->member) }}</td>
        <td>{{ ucwords($t->team) }}</td>
        <td>{{ $t->time }}</td>
    </tr>
@endforeach
