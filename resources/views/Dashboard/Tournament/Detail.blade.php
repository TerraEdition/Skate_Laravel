@php
    use App\Helpers\Date;
@endphp

@extends('Dashboard.Layout.Main')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <x-button.back url="tournament" />
    </div>
    <x-alert.danger />
    </div>
@endsection
