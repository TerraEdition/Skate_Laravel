@extends('Dashboard.Layout.Main')
@section('content')
    <div class="d-flex gap-2">
        <x-button.back url="participant" />
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Pilih Mode
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item">Input Manual</a></li>
                <li><a class="dropdown-item">Stopwatch</a></li>
            </ul>
        </div>
        <div class="btn btn-sm btn-success" id="new-screen"><i class="fa-solid fa-tv"></i> Buka Layar Turnamen</div>
    </div>
    <x-alert />
    <div class="d-flex justify-content-between mt-3">
        <div class="fs-5"> Peserta Turnamen <b>{{ $group->tournament }}</b> di Grup <b>{{ $group->group }}</b></div>
        @if ($group->status == 1)
            <div class="btn btn-outline-primary" id="close_group">Tutup Pertandingan Grup Ini</div>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Tim</th>
                <th>Waktu</th>
                <th>Aksi</th>
            </tr>
            @foreach ($participant as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->member }}</td>
                    <td>{{ $p->team }}</td>
                    <td id="time_participant{{ $p->participant_id }}">{{ $p->time ?? '00:00:000' }}</td>
                    <td>
                        <div data-participant_id="{{ $p->participant_id }}" data-participant_name="{{ $p->member }}"
                            class="btn btn-sm btn-primary" id="show_stopwatch">
                            Mulai</div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="modal fade" id="timeCompetition" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="timeCompetitionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="timeCompetitionLabel">Stopwatch</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h1 id="show_time">00:00:000</h1>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="start_time">Mulai</button>
                    <button type="button" class="btn btn-danger d-none" id="finish_time"
                        data-participant_id="">Selesai</button>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="{{ asset('js/peer.min.js') }}"></script>
    <script src="{{ asset('js/participant/competition.js') }}"></script>
@endsection
