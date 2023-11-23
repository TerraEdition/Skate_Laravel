@extends('Dashboard.Layout.Main')
@section('css')
<link rel="stylesheet" href="<?= asset('css/upload_image.css') ?>">
@endsection
@section('js')
<script src="<?= asset('js/upload_image.js') ?>"></script>
@endsection
@section('content')
<form action="{{ url()->current() }}" method="POST" enctype="multipart/form-data">
    <div class="d-flex justify-content-between mb-3">
        <x-button.back url="team/{{ $team_slug }}/member/{{ $data->slug }}" />
    </div>
    <x-alert />
    <div class="row">
        <div class="col-md-8">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="member" class="form-label">Nama
                    <x-required />
                </label>
                <input type="text" id="member" name="member" class="form-control @error('member') is-invalid @enderror"
                    value="{{ old('member', $data->member) }}">
                @error('member')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <input type="text" id="address" name="address"
                    class="form-control @error('address') is-invalid @enderror"
                    value="{{ old('address', $data->address) }}">
                @error('address')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="birth" class="form-label">Tanggal Lahir
                    <x-required />
                </label>
                <input type="number" class="form-control @error('birth') is-invalid @enderror" id="birth" min="1990"
                    name="birth" value="{{ old('birth', $data->birth) }}" max="{{ date('Y') }}">
                @error('birth')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $data->email) }}">
                @error('email')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">No HP</label>
                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone', $data->phone) }}">
                @error('phone')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Jenis Kelamin
                    <x-required />
                </label>
                <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror">
                    <option value="1" {{ old('gender', $data->gender) == '1' ? 'selected' : '' }}>Putra</option>
                    <option value="2" {{ old('gender', $data->gender) == '2' ? 'selected' : '' }}>Putri</option>
                </select>
                @error('gender')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <img src="{{ asset('storage/image/profile/default.png') }}" alt="foto"
                class="img-fluid rounded-circle img-preview" />
            <div class=" mb-3">
                <label for="image" class="form-label">Foto</label>
                <input type="file" id="image" name="image" accept="image/*" onchange="previewImg()" />




                @error('image')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <x-button.submit />
    </div>
</form>
@endsection