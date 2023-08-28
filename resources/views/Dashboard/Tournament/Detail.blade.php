@php
    use App\Helpers\Date;
    use App\Helpers\Convert;
    use App\Helpers\Format;
@endphp

@extends('Dashboard.Layout.Main')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <x-button.back url="tournament" />
    </div>
    <x-alert.danger />
    <div class="border-bottom border-3 border-secondary p-2">
        <h3>Turnamen : {{ $data->tournament }}</h3>
        {{ Date::format_long($data->start_date) }}
        -
        {{ Date::format_long($data->end_date) }}
    </div>
    <br>
    <div class="table-responsive">
        <h5>Rincian : </h5>
        <table class="table">
            <tr>
                <td>Jam Mulai</td>
                <td>{{ $data->start_time }}</td>
            </tr>
            <tr>
                <td>Jam Selesai</td>
                <td>{{ $data->end_time }}</td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>{{ $data->location }}</td>
            </tr>
            <tr>
                <td colspan="2">{!! Format::unclean($data->description) !!}</td>
            </tr>
        </table>
    </div>
    <div class="table-responsive">
        <div class="d-flex justify-content-between">
            <h5>Grup yang tersedia</h5>
            <x-button.create url="tournament/{{ $data->slug }}/group" />
        </div>
        <table class="table">
            <tr>
                <th>#</th>
                <th>Group</th>
                <th>Batas Umur</th>
                <th>Kategori</th>
                <th>Peserta</th>
                <th>Aksi</th>
            </tr>
            @foreach ($group as $g)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $g->group }}</td>
                    <td>{{ $g->min_age }} - {{ $g->max_age }} Tahun</td>
                    <td>{{ Convert::gender($g->gender, false) }}</td>
                    <td>{{ $g->total_participant }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <x-button.detail url="tournament/{{ $data->slug }}/group/{{ $g->slug }}" />
                            <x-button.delete url="tournament/{{ $data->slug }}/group/{{ $g->slug }}" />
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
