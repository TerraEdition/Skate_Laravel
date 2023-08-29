@extends('Dashboard.Layout.Main')
@section('css')
<link rel="stylesheet" href="<?= asset('css/upload_image.css') ?>">
@endsection
@section('js')
<script src="<?= asset('js/upload_image.js') ?>"></script>
@endsection

@section('content')

<form action="{{url()->current()}}" method="POST" enctype="multipart/form-data">
    <div class="d-flex justify-content-between mb-3">
        <x-button.back url="team" />
    </div>

    <x-alert />
    <div class="row">
        <div class="col-md-8">
            @csrf
            <div class="mb-3">
                <label for="team" class="form-label">Tim
                    <x-required />
                </label>
                <input type="text" class="form-control" id="team" name="team" class="@error('team') is-invalid @enderror" value="{{old('team')}}">
                @error('team')
                <small class="text-danger ms-2">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Alamat
                    <x-required />
                </label>
                <input type="text" class="form-control" id="address" name="address" class="@error('address') is-invalid @enderror" value="{{old('address')}}">
                @error('address')
                <small class="text-danger ms-2">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="coach" class="form-label">Pelatih
                    <x-required />
                </label>
                <input type="text" class="form-control" id="coach" name="coach" class="@error('coach') is-invalid @enderror" value="{{old('coach')}}">
                @error('coach')
                <small class="text-danger ms-2">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email
                    <x-required />
                </label>
                <input type="email" class="form-control" id="email" name="email" class="@error('email') is-invalid @enderror" value="{{old('email')}}">
                @error('email')
                <small class="text-danger ms-2">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="web" class="form-label">URL Website Resmi</label>
                <input type="text" class="form-control" id="web" name="web" placeholder="https://" class="@error('web') is-invalid @enderror" value="{{old('web')}}">
                @error('web')
                <small class="text-danger ms-2">{{$message}}</small>
                @enderror
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col">
                        <label for="contact_name" class="form-label">Nama Kontak
                            <x-required />
                        </label>
                        <input type="text" class="form-control" id="contact_name" name="contact[0][name]" class="@error('contact.0.name') is-invalid @enderror" value="{{old('contact.0.name')}}">
                        @error('contact.*.name')
                        <small class="text-danger ms-2">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="col">
                        <label for="phone" class="form-label">No HP
                            <x-required />
                        </label>
                        <input type="number" class="form-control" id="phone" name="contact[0][phone]" class="@error('contact.0.phone') is-invalid @enderror" value="{{old('contact.0.phone')}}">
                        @error('contact.*.phone')
                        <small class="text-danger ms-2">{{$message}}</small>
                        @enderror
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <img src="{{asset('storage/image/profile/default.png')}}" alt="logo" class="img-fluid rounded-circle img-preview" />
            <div class=" mb-3">
                <label for="image" class="form-label">Logo</label>
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