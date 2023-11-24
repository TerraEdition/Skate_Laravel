@extends('Dashboard.Layout.Main')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <x-button.create url="tournament" />
    </div>
    <x-alert />
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <td>Turnamen</td>
                    <td>Tanggal Acara</td>
                    <td>Lokasi</td>
                    <td>Aksi</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->tournament }}</td>
                        <td>{{ Date::format_short($r->start_date) }} - {{ Date::format_short($r->end_date) }}</td>
                        <td>{{ $r->location }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <x-button.detail url="tournament/{{ $r->slug }}" />
                                @if ($r->total_group == 0)
                                    <x-button.delete url="tournament/{{ $r->slug }}" />
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $data->links('Paginate.Custom') }}
    </div>
@endsection
