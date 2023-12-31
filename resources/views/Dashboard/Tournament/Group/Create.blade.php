@extends('Dashboard.Layout.Main')
@section('css')
    <link rel="stylesheet" href="<?= asset('css/upload_image.css') ?>">
@endsection

@section('js')
    <script src="https://cdn.tiny.cloud/1/plrqcl0e028uwokisqjivacjlga369r1mz1qrwwahy900kf1/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="<?= asset('js/upload_image.js') ?>"></script>
    <script src="<?= asset('js/tinymce.js') ?>"></script>
@endsection
@section('content')
    <form action="{{ url()->current() }}" method="POST" enctype="multipart/form-data">
        <div class="d-flex justify-content-between mb-3">
            <x-button.back url="tournament/{{ $tournament_slug }}" />
        </div>
        <x-alert />
        <div class="row">
            <div class="col-md-8">
                @csrf
                <div class="mb-3">
                    <label for="group" class="form-label">Grup
                        <x-required />
                    </label>
                    <input type="text" class="form-control" id="group" name="group"
                        class="@error('group') is-invalid @enderror" value="{{ old('group') }}">
                    @error('group')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Kategori
                        <x-required />
                    </label>
                    <br>
                    <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                        <input type="radio" class="btn-check" name="category[]" value="1" id="btncheck1"
                            @if (in_array(1, old('category', []))) checked @endif autocomplete="off">
                        <label class="btn btn-outline-primary" for="btncheck1">Putra</label>

                        <input type="radio" class="btn-check" name="category[]" value="2" id="btncheck2"
                            @if (in_array(2, old('category', []))) checked @endif autocomplete="off">
                        <label class="btn btn-outline-success" for="btncheck2">Putri</label>
                    </div>
                    @error('category')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col">
                            <label for="min_age" class="form-label">Batas Bawah Tahun Kelahiran
                                <x-required />
                            </label>
                            <input type="text" pattern="[0-9]+" maxlength="4" minlength="4" class="form-control"
                                id="min_age" name="min_age" class="@error('min_age') is-invalid @enderror"
                                value="{{ old('min_age') }}">
                            @error('min_age')
                                <small class="text-danger ms-2">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="max_age" class="form-label">Batas Atas Tahun Kelahiran
                                <x-required />
                            </label>
                            <input type="text" pattern="[0-9]+" maxlength="4" minlength="4" class="form-control"
                                id="max_age" name="max_age" class="@error('max_age') is-invalid @enderror"
                                value="{{ old('max_age') }}">
                            @error('max_age')
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
