@extends('Dashboard.Layout.Main')
@section('css')
<link rel="stylesheet" href="<?= asset('css/upload_image.css') ?>">
@endsection
@section('js')
<script src="<?= asset('js/upload_image.js') ?>"></script>
@endsection
@section('content')
<form action="{{url()->current()}}/{{$slug}}" method="POST" enctype="multipart/form-data">
    <div class="d-flex justify-content-between mb-3">
        <x-button.back url="team/{{$slug}}" />
    </div>
    <x-alert.danger />
    <div class="row">
        <div class="col-md-8">
            @csrf
            <div class="mb-3">
                <label for="member" class="form-label">Nama
                    <x-required />
                </label>
                <input type="text" class="form-control" id="member" name="member" class="@error('member') is-invalid @enderror" value="{{old('member')}}">
                @error('member')
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
                <label for="birth" class="form-label">Tanggal Lahir
                    <x-required />
                </label>
                <input type="date" class="form-control" id="birth" name="birth" class="@error('birth') is-invalid @enderror" value="{{old('birth')}}" max="{{(date('Y')-3)}}-12-31">
                @error('birth')
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
            <div class="mb-3">
                <label for="phone" class="form-label">No HP</label>
                <input type="text" class="form-control" id="phone" name="phone" class="@error('phone') is-invalid @enderror" value="{{old('phone')}}">
                @error('phone')
                <small class="text-danger ms-2">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Jenis Kelamin
                    <x-required />
                </label>
                <select class="form-control" id="gender" name="gender" class="@error('gender') is-invalid @enderror" value="{{old('gender')}}">
                    <option value="1">Putra</option>
                    <option value="2">Putri</option>
                </select>
                @error('gender')
                <small class="text-danger ms-2">{{$message}}</small>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <img src="{{asset('storage/image/profile/default.png')}}" alt="foto" class="img-fluid rounded-circle img-preview" />
            <div class=" mb-3">
                <label for="image" class="form-label">Foto</label>
                <input class="form-control" type="file" id="image" name="image" accept="image/*" onchange="previewImg()" />

                @error('image')
                <small class="text-danger ms-2">{{$message}}</small>
                @enderror
            </div>
        </div>
        <x-button.submit />
    </div>
</form>
@endsection