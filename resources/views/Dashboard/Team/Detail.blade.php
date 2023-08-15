@extends('Dashboard.Layout.Main')
@section('content')
<div class="container pt-3">
    <div class="d-flex gap-2 mb-3">
        <x-button.edit url="team/edit/{{$data->slug}}" />
        <x-button.back url="team" />
    </div>

    <x-alert.danger />
    <div class="h4 pb-2 my-3 text-dark border-bottom border-dark">
        <h3>{{$data->team}}</h3>
    </div>
    <div class="row">
        <div class="col-md-8">
            <h5>Data Tim</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td>Pelatih</td>
                        <td>:</td>
                        <td> {{$data->coach}}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td> {{$data->address}}</td>
                    </tr>
                    <tr>
                        <td>No HP</td>
                        <td>:</td>
                        <td> {{$data->phone}}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td> {{$data->email}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <img src="{{asset('storage/image/teams/' . $data->image)}}" alt="logo" class="img-fluid w-75 rounded-circle" />
        </div>
    </div>
    <div class="d-flex gap-2 mb-3">
        <x-button.create url="member" params="?team={{$data->slug}}" label="Tambah Anggota" />
    </div>
    <h5>Data Anggota</h5>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <td>Anggota</td>
                    <td>Jenis Kelamin</td>
                    <td>Tanggal Lahir</td>
                    <td>Umur</td>
                    <td>Aksi</td>
                </tr>
            </thead>
            <tbody>
                @foreach($member as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $r['member'] }}</td>
                    <td>{{ $r['gender'] }}</td>
                    <td>{{ $r['birth'] }}</td>
                    <td>{{ $r['age'] }} Tahun</td>
                    <td>
                        <div class="d-flex gap-2">
                            <x-button.detail url="team" :id="$r['slug']" />
                            <x-button.delete url="team" :id="$r['slug']" />
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>








































































@endsection