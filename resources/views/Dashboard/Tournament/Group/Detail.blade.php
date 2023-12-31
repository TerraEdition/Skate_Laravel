@php
    use App\Helpers\Convert;
@endphp
@extends('Dashboard.Layout.Main')
@section('css')
    <link rel="stylesheet" href="<?= asset('css/upload_image.css') ?>">
@endsection

@section('js')
    <script src="https://cdn.tiny.cloud/1/plrqcl0e028uwokisqjivacjlga369r1mz1qrwwahy900kf1/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="<?= asset('js/upload_image.js') ?>"></script>
    <script src="<?= asset('js/tinymce.js') ?>"></script>
@endsection
@section('content')
    <div class="d-flex justify-content-start mb-3 gap-2">
        <x-button.back url="tournament/{{ $tournament_slug }}" />
    </div>
    <x-alert />
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <td>Grup</td>
                <td>{{ $data->group }}</td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>{{ Convert::gender($data->gender, false) }}</td>
            </tr>
            <tr>
                <td>Batas Umur</td>
                <td>{{ $data->min_age }} - {{ $data->max_age }}</td>
            </tr>
            <tr>
                <td>Batas Peserta</td>
                <td>{{ $data->max_participant }} Peserta ({{ $data->max_per_team }} Peserta / Tim)</td>
            </tr>

            <tr>
                <td>Tim yang terdaftar</td>
                <td>{{ $data->team_register }}</td>
            </tr>
            <tr>
                <td>Total Peserta yang sudah terdaftar</td>
                <td>{{ $data->total_participant }}</td>
            </tr>
        </table>
    </div>

    <div class="table-responsive">
        <div class="d-flex justify-content-between">
            <h5>Peserta</h5>
            @if ($data->status == 0 && !$is_close && strtotime($data->start_date) >= strtotime(date('Y-m-d')))
                <x-button.create url="tournament/{{ $tournament_slug }}/group/{{ $data->slug }}/participant" />
            @endif
        </div>
        <table class="table">
            <tr>
                <th>#</th>
                <th>Tim</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Umur</th>
                <th>Aksi</th>
            </tr>
            @foreach ($participant as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $r->team }}</td>
                    <td>{{ $r->member }}</td>
                    <td>{{ Convert::gender($r->gender, false) }}</td>
                    <td>{{ Date::calculate_year($r->birth) }} Tahun</td>
                    <td>
                        @if ($data->status == '0' && !$is_close && strtotime($data->start_date) >= strtotime(date('Y-m-d')))
                            <x-button.delete
                                url="tournament/{{ $tournament_slug }}/group/{{ $data->slug }}/participant/{{ $r->participant_id }}" />
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        {{ $participant->links('Paginate.Custom') }}
    </div>
@endsection
