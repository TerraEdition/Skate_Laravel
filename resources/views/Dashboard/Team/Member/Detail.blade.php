@extends('Dashboard.Layout.Main')
@section('content')
    <div class="d-flex gap-2">
        <x-button.edit url="team/{{ $team_slug }}/member/edit/{{ $data->slug }}" />
        <x-button.back url="team/{{ $team_slug }}" />
    </div>
    <x-alert />
    <div class="row">
        <div class="col-8">
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $data->member }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>:</td>
                        <td>{{ Convert::gender($data->gender, false) }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>:</td>
                        <td>{{ $data->birth }} ({{ Date::calculate_year($data->birth) }} Tahun)</td>
                    </tr>
                    <tr>
                        <td>Total Pertandingan</td>
                        <td>:</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td colspan="3"><button class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#modal-match-log">
                                Riwayat Pertandingan
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-4">
            <img src="{{ asset('storage/image/teams/member/' . $data->image) }}" alt="logo"
                class="img-fluid w-75 rounded-circle" />
        </div>
    </div>

    <h5>Turnamen mendatang yang diikuti</h5>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Turnamen</th>
                    <th>Grup</th>
                    <th>Jadwal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tournament_incoming as $ti)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ti->tournament }}</td>
                        <td>{{ $ti->group }}</td>
                        <td>{{ Date::format_short($ti->start_date) }} - {{ Date::format_short($ti->end_date) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tournament_incoming->links('Paginate.Custom') }}
    </div>

    <div class="modal fade" id="modal-match-log" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Riwayat Pertandingan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection
