<style>
    /* Atur elemen <thead> sebagai posisi sticky untuk tetap terlihat */
    thead {
        position: sticky;
        top: 0;
        background-color: #2b445c;
        /* Sesuaikan dengan latar belakang tabel Anda */
    }

    /* Atur elemen <tbody> sebagai overflow yang bisa di-scroll */
    tbody {
        max-height: 100vh;
        /* Atur tinggi maksimum sesuai kebutuhan */
        overflow-y: auto;
    }
</style>

@if ($data->status < 2)
    <table class="table text-white">
        <thead>
            <tr>
                <th colspan="3">
                    <h1>{{ strtoupper($data->tournament) }}</h1>
                    <h5>{{ strtoupper($data->group) }}</h5>
                </th>
            </tr>
            <tr>
                <th>No.</th>
                <th>Nama Peserta</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody class="h-75 overflow-y">
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
                <th colspan="5">
                    <h1>{{ strtoupper($data->tournament) }}</h1>
                    <h5>{{ strtoupper($data->group) }}</h5>
                </th>
            </tr>
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
                    <td>{{ $p->no_participant }}</td>
                    <td>{{ $p->member }}</td>
                    <td>{{ $p->team }}</td>
                    <td>{{ $p->time ? $p->time : '00:00' }}</td>
                    <td>{{ $loop->iteration }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
