@php
use App\Helpers\Date;
use App\Helpers\Convert;
@endphp

@extends('Dashboard.Layout.Main')
@section('content')
<div class="d-flex gap-2 mb-3">
    <x-button.edit url="team/edit/{{ $data->slug }}" />
    <x-button.back url="team" />
</div>

<x-alert />
<div class="h4 pb-2 my-3 text-dark border-bottom border-dark">
    <h3>{{ $data->team }}</h3>
</div>
<div class="row mb-4">
    <div class="col-md-8">
        <h5>Data Tim</h5>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <td>Pelatih</td>
                    <td>:</td>
                    <td> {{ $data->coach }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td> {{ $data->address }}</td>
                </tr>
                <tr>
                    <td>No HP</td>
                    <td>:</td>
                    <td> {{ $data->phone }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td> {{ $data->email }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        <img src="{{ asset('storage/image/teams/' . $data->image) }}" alt="logo" class="img-fluid w-75 rounded-circle" />
    </div>
</div>
<div class="d-flex justify-content-between">
    <h5>Data Anggota</h5>
    <x-button.create url="team/{{ $data->slug }}/member" label="Tambah Anggota" />
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Anggota</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Umur</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($member as $r)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $r->member }}</td>
                <td>{{ Convert::gender($r->gender, false) }}</td>
                <td>{{ Date::format_long($r->birth) }}</td>
                <td>{{ Date::calculate_age($r->birth) }} Tahun</td>
                <td>
                    <div class="d-flex gap-2">
                        <x-button.detail url="team/{{$data->slug}}/member/{{$r->slug}}" />
                        <x-button.delete url="team/{{$data->slug}}/member/{{$r->slug}}" />
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $member->links('Paginate.Custom') }}
</div>









@endsection