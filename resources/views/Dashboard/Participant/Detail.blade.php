@extends('Dashboard.Layout.Main')
@section('content')
    <x-alert />
    <div class="d-flex gap-2">
        <x-button.back url="participant" />
        <a class="btn btn-sm btn-success" href="{{ url()->current() }}/export-excel" target="_blank"><i
                class="fa-solid fa-file-excel"></i> Export Excel</a>
    </div>

    <div class="d-flex justify-content-between mt-3">
        <div class="fs-5"> Peserta Turnamen <b>{{ $group->tournament }}</b> di Grup <b>{{ $group->group }}</b></div>
        @if ($group->status != 2)
            <a href="{{ url()->current() }}/competition"
                class="btn btn-outline-primary">{{ $group->status == 0 ? 'Mulai Pertandingan' : 'Lanjutkan Pertandingan' }}
            </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Tim</th>
                <th>Waktu</th>
                @if ($group->status == 2)
                    <th>Posisi</th>
                @endif
            </tr>
            @foreach ($participant as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->member }}</td>
                    <td>{{ $p->team }}</td>
                    <td>{{ $p->time ?? '00:00' }}</td>
                    @if ($group->status == 2)
                        <td>{{ $loop->iteration }}</td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
@endsection
