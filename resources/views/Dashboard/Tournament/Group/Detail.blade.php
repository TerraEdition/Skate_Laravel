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
    <div class="d-flex justify-content-start mb-3">
        <x-button.create url="tournament/group/{{ $tournament_slug }}/{{ $data->slug }}/participant" />
        <x-button.back url="tournament/{{ $tournament_slug }}" />
    </div>
    <x-alert.danger />
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>Grup</td>
                    <td>Kategori</td>
                    <td>Tim yang terdaftar</td>
                    <td>Total Peserta yang sudah terdaftar</td>
                    <td>Aksi</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data->group }}</td>
                    <td>{{ Convert::gender($data->gender, true) }}</td>
                    <td>{{ $data->team_register }}</td>
                    <td>{{ $data->total_participant }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <x-button.detail url="tournament" :id="$data->slug" />
                            <x-button.delete url="tournament" :id="$data->slug" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection
