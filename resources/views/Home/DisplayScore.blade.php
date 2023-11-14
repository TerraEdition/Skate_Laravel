@if($participant->isEmpty())
<tr>
    <td colspan="5" class="table-danger text-center"><br>Belum ada Peserta<br><br></td>
</tr>
@else
@foreach ($participant as $t)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $t->no_participant }}</td>
    <td>{{ ucwords($t->member) }}</td>
    <td>{{ ucwords($t->team) }}</td>
    <td>{{ $t->time }}</td>
</tr>
@endforeach
@endif