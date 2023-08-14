@extends('Dashboard.Layout.Main')
@section('content')
<div class="container pt-3">
    <form action="{{url()->current()}}" method="POST" enctype="multipart/form-data">
        <div class="d-flex justify-content-between mb-3">
            <x-button.back url="team" />
        </div>

        <x-alert.danger />
        <div class="row">
            <div class="col-md-8">
                @csrf
                <div class="mb-3">
                    <label for="team" class="form-label">Team</label>
                    <input type="text" class="form-control" id="team" name="team" class="@error('team') is-invalid @enderror" value="{{old('team')}}">
                    @error('team')
                    <small class="text-danger ms-2">{{$message}}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="address" name="address" class="@error('address') is-invalid @enderror" value="{{old('address')}}">
                    @error('address')
                    <small class="text-danger ms-2">{{$message}}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="coach" class="form-label">Pelatih</label>
                    <input type="text" class="form-control" id="coach" name="coach" class="@error('coach') is-invalid @enderror" value="{{old('coach')}}">
                    @error('coach')
                    <small class="text-danger ms-2">{{$message}}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">HP</label>
                    <input type="number" class="form-control" id="phone" name="phone" class="@error('phone') is-invalid @enderror" value="{{old('phone')}}">
                    @error('phone')
                    <small class="text-danger ms-2">{{$message}}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" class="@error('email') is-invalid @enderror" value="{{old('email')}}">
                    @error('email')
                    <small class="text-danger ms-2">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <img src="{{asset('storage/image/profile/default.png')}}" alt="logo" class="img-fluid rounded-circle" />
                <div class=" mb-3">
                    <label for="image" class="form-label">Logo</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*" />
                    @error('image')
                    <small class="text-danger ms-2">{{$message}}</small>
                    @enderror
                </div>
            </div>
            <x-button.submit />
        </div>
    </form>
</div>

























@endsection