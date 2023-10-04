@extends('Auth.Layout.Main')
@section('css')
@endsection
@section('content')
<form action="{{ url()->current() }}" method="POST" class="d-flex flex-column justify-content-center">
    @csrf
    <div class="my-4">
        <h2>Masuk</h2>
        <div class="text-muted">Masukkan Identitas anda untuk masuk ke aplikasi</div>
        <br>
    </div>
    <div class="form-group mt-3">
        <label for="email" class="form-label">Email *</label>
        <input type="email" name="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email') }}" placeholder="mail@simple.com" autofocus>
        @error('email')
        <small class="invalid-feedback">{{ $message }}</small>
        @enderror
    </div>
    <div class="form-group my-3">
        <label for="password" class="form-label">Password *</label>
        <input type="password" name="password" placeholder="Password"
            class="form-control @error('password') is-invalid @enderror" placeholder="Min 8 karakter">
        @error('password')
        <small class="invalid-feedback">{{ $message }}</small>
        @enderror
    </div>
    <!-- <div class="form-group">
            <div class="d-flex justify-content-between my-3">
                <div>
                    <input type="checkbox"> Ingat perangkat ini
                </div>
                <div>
                    <a href="/forgot-password" class="text-indigo text-decoration-none">Lupa Password ?</a>
                </div>
            </div>
        </div> -->
    <button type="submit" class="form-control rounded btn btn-indigo text-light p-2 fs-5">Masuk</button>
    {{-- <div class="mt-3">
            <span class="text-indigo"> Belum mendaftar? <a href="/register" class="text-indigo text-decoration-none">Buat
                    akun</a></span>
        </div> --}}
</form>
@endsection