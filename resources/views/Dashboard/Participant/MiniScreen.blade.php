<h1>{{strtoupper($data->tournament)}}</h1>
<h5>{{strtoupper($data->group)}}</h5>
<table class="table text-white">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Peserta</th>
            <th>Waktu</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($participant as $p)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$p->member}}</td>
            <td>{{$p->time ? $p->time : '00:00:000'}}</td>
        </tr>
        @endforeach
    </tbody>
</table>