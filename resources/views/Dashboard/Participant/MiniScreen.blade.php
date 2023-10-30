<h1>{{ strtoupper($data->tournament) }}</h1>
<h5>{{ strtoupper($data->group) }}</h5>
@if ($data->status < 2)
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
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->member }}</td>
                    <td>{{ $p->time ? $p->time : '00:00' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <table class="table text-white">
        <thead>
            <tr>
                <th>No. BIB</th>
                <th>Nama Peserta</th>
                <th>Tim</th>
                <th>Waktu</th>
                <th>Posisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($participant as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->no_participant }}</td>
                    <td>{{ $p->member }}</td>
                    <td>{{ $p->team }}</td>
                    <td>{{ $p->time ? $p->time : '00:00' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
