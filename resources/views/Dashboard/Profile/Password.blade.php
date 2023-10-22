@extends('Dashboard.Layout.Main')
@section('css')
    <link rel="stylesheet" href="<?= asset('css/upload_image.css') ?>">
@endsection
@section('js')
    <script src="<?= asset('js/upload_image.js') ?>"></script>
@endsection

@section('content')
    <x-alert />
    <form action="{{ url()->current() }}" method="POST" class="w-50">
        <div class="mb-3">
            <label for="password_lama" class="form-label">Password Lama
                <x-required />
            </label>
            <input type="password" class="form-control" id="password_lama" name="password_lama"
                class="@error('password_lama') is-invalid @enderror" value="{{ old('password_lama') }}">
            @error('password_lama')
                <small class="text-danger ms-2">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password_baru" class="form-label">Password Baru
                <x-required />
            </label>
            <input type="password" class="form-control" id="password_baru" name="password_baru"
                class="@error('password_baru') is-invalid @enderror" value="{{ old('password_baru') }}">
            @error('password_baru')
                <small class="text-danger ms-2">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="konfirmasi_password" class="form-label">Konfirmasi Password
                <x-required />
            </label>
            <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password"
                class="@error('konfirmasi_password') is-invalid @enderror" value="{{ old('konfirmasi_password') }}">
            @error('konfirmasi_password')
                <small class="text-danger ms-2">{{ $message }}</small>
            @enderror
        </div>
    </form>
@endsection
