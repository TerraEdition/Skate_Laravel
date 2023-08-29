@extends('Dashboard.Layout.Main')
@section('content')
<form action="{{ url()->current() }}" method="POST" enctype="multipart/form-data">
    <div class="d-flex justify-content-between mb-3">
        <x-button.back url="team/{{ $slug }}" />
    </div>
    <x-alert />
</form>
@endsection