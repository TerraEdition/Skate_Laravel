@extends('Dashboard.Layout.Main')
@section('content')
    <div class="d-flex justify-content-start gap-3 mb-3">
        <x-button.back url="tournament" />
        <a class="btn btn-primary btn-sm" href="{{ url()->current() }}/gallery"><i class="fa-regular fa-images"></i>
            Gallery
        </a>
        <!-- <a class="btn btn-primary btn-sm" href="{{ url()->current() }}/export"><i class="fa-solid fa-file-excel"></i> Ekspor Template Excel</a> -->
    </div>
    <x-alert />
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
            @if (strtotime($data->start_date) > strtotime(date('Y-m-d')))
                <x-button.create url="tournament/{{ $data->slug }}/group" />
            @endif
        </div>
        <table class="table">
            <tr>
                <th>#</th>
                <th>Group</th>
                <th>Tahun Kelahiran</th>
                <th>Kategori</th>
                <th>Peserta</th>
                <th>Aksi</th>
            </tr>
            @foreach ($group as $g)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $g->group }}</td>
                    <td>{{ $g->min_age }} - {{ $g->max_age }}</td>
                    <td>{{ Convert::gender($g->gender, false) }}</td>
                    <td>{{ $g->total_participant }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <x-button.detail url="tournament/{{ $data->slug }}/group/{{ $g->slug }}" />
                            @if ($g->total_participant == 0)
                                <x-button.delete url="tournament/{{ $data->slug }}/group/{{ $g->slug }}" />
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    {{ $group->links('Paginate.Custom') }}
@endsection
