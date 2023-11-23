@php
    $nowClass = $status == 'now' ? 'btn-primary' : 'btn-outline-primary';
    $incomingClass = $status == 'incoming' ? 'btn-primary' : 'btn-outline-primary';
    $completedClass = $status == 'completed' ? 'btn-primary' : 'btn-outline-primary';
@endphp

@extends('Dashboard.Layout.Main')
@section('content')
    <x-alert />
    <div class="d-flex justify-content-end my-3">
        <div class=" btn-group" role="group" aria-label="Basic mixed styles example">
            <a href="{{ url()->current() }}?status=now" type="button" class="btn {{ $nowClass }}">Sedang Berjalan</a>
            <a href="{{ url()->current() }}?status=incoming" type="button" class="btn {{ $incomingClass }}">Akan Datang</a>
            <a href="{{ url()->current() }}?status=completed" type="button" class="btn {{ $completedClass }}">Selesai</a>
        </div>
    </div>
    @if ($data->isEmpty())
        <x-data-empty />
    @endif
    <div class="d-flex flex-wrap gap-2">
        @foreach ($data as $r)
            <a href="{{ url()->current() }}/{{ $r->tournament_slug }}/{{ $r->slug }}"
                class="d-block text-decoration-none">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-end">
                            <h4>{{ $r->tournament }}</h4>
                            <h6>{{ $r->group }}</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <table>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td>{{ Date::format_short($r->start_date) }} s/d {{ Date::format_short($r->end_date) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Lokasi</td>
                                <td>:</td>
                                <td>{{ $r->location }}</td>
                            </tr>
                            <tr>
                                <td>Peserta</td>
                                <td>:</td>
                                <td>{{ $r->total_participant }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <div class="badge text-bg-primary">
                            {{ Convert::status_tournament($status, false) }}
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    {{ $data->links('Paginate.Custom') }}
@endsection
