@extends('Dashboard.Layout.Main')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <x-button.create url="team" />
</div>
<x-alert.danger />
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <td>Tim</td>
                <td>Tanggal</td>
                <td>Aksi</td>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $r)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $r->team }}</td>
                <td>{{ $r->updated_at }}</td>
                <td>
                    <div class="d-flex gap-2">
                        <x-button.detail url="team" :id="$r->slug" />
                        <x-button.delete url="team" :id="$r->slug" />
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection