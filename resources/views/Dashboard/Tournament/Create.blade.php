@extends('Dashboard.Layout.Main')
@section('css')
<link rel="stylesheet" href="<?= asset('css/upload_image.css') ?>">
@endsection

@section('js')
<script src="https://cdn.tiny.cloud/1/plrqcl0e028uwokisqjivacjlga369r1mz1qrwwahy900kf1/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="<?= asset('js/upload_image.js') ?>"></script>
<script src="<?= asset('js/tinymce.js') ?>"></script>
@endsection
@section('content')
<form action="{{ url()->current() }}" method="POST" enctype="multipart/form-data">
    <div class="d-flex justify-content-between mb-3">
        <x-button.back url="tournament" />
    </div>
    <x-alert />
    <div class="row">
        <div class="col-md-8">
            @csrf
            <div class="mb-3">
                <label for="tournament" class="form-label">Turnamen
                    <x-required />
                </label>
                <input type="text" class="form-control" id="tournament" name="tournament" class="@error('tournament') is-invalid @enderror" value="{{ old('tournament') }}">
                @error('tournament')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Lokasi
                    <x-required />
                </label>
                <input type="text" class="form-control" id="location" name="location" class="@error('location') is-invalid @enderror" value="{{ old('location') }}">
                @error('location')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col">
                        <label for="start_date" class="form-label">Tanggal Mulai
                            <x-required />
                        </label>
                        <input type="date" class="form-control" id="start_date" name="start_date" class="@error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}">
                        @error('start_date')
                        <small class="text-danger ms-2">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="end_date" class="form-label">Tanggal Selesai
                            <x-required />
                        </label>
                        <input type="date" class="form-control" id="end_date" name="end_date" class="@error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}">
                        @error('end_date')
                        <small class="text-danger ms-2">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col">
                        <label for="start_time" class="form-label">Jam Mulai
                            <x-required />
                        </label>
                        <input type="time" class="form-control" id="start_time" name="start_time" class="@error('start_time') is-invalid @enderror" value="{{ old('start_time') }}">
                        @error('start_time')
                        <small class="text-danger ms-2">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="end_time" class="form-label">Jam Selesai
                            <x-required />
                        </label>
                        <input type="time" class="form-control" id="end_time" name="end_time" class="@error('end_time') is-invalid @enderror" value="{{ old('end_time') }}">
                        @error('end_time')
                        <small class="text-danger ms-2">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <textarea name="description" id="description" cols="30" rows="10">
                {{ old('description') }}
                </textarea>
                @error('description')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
    <x-button.submit />
</form>
@endsection