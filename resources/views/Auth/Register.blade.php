@extends('Auth.Layout.Main')
@section('content')
    <form action="<?= url()->current() ?>" method="POST" class="d-flex flex-column justify-content-center">
        @csrf
        <div class="form-group my-1">
            <input type="text" name="name" placeholder="Nama" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}">
            @error('name')
                <small class="invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group my-1">
            <input type="email" name="email" placeholder="Email"
                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
            @error('email')
                <small class="invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group my-1">
            <input type="password" name="password" placeholder="Password"
                class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <small class="invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group my-1">
            <input type="password" name="confirmation_password" placeholder="Konfirmasi Password"
                class="form-control @error('confirmation_password') is-invalid @enderror">
            @error('confirmation_password')
                <small class="invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Daftar</button>
    </form>
@endsection
