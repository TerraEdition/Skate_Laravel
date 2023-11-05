@extends('Dashboard.Layout.Main')
@section('css')
@endsection
@section('js')
@endsection

@section('content')
    <x-alert />
    <div class="row">
        <div class="col-6">
            @include('Dashboard.SettingGroup.Create.Part' . $step)
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    Info Group
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Grup</td>
                            <td>:</td>
                            <td>{{ $group->group }}</td>
                        </tr>
                        <tr>
                            <td>Kelompok</td>
                            <td>:</td>
                            <td>{{ Convert::gender($group->gender, false) }}</td>
                        </tr>
                        <tr>
                            <td>Tahun Lahir</td>
                            <td>:</td>
                            <td>{{ $group->min_age }} - {{ $group->max_age }}</td>
                        </tr>
                        <tr>
                            <td>Peserta Terdaftar</td>
                            <td>:</td>
                            <td>{{ $group->total_participant }} </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
