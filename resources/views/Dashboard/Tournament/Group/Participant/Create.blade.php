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
            <x-button.back url="tournament/{{ $tournament_slug }}/group/{{ $group->slug }}" />
        </div>
        <x-alert.danger />
        <div class="row">
            <div class="col-md-8">
                @csrf
                <div class="mb-3">
                    <label for="team" class="form-label">Tim
                        <x-required />
                    </label>
                    <input type="text" class="form-control" id="team" name="team"
                        class="@error('team') is-invalid @enderror" value="{{ old('team') }}">
                    @error('team')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>

            </div>
        </div>
        <x-button.submit />
    </form>
@endsection
