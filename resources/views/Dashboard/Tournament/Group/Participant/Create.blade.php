@extends('Dashboard.Layout.Main')
@section('css')
@endsection

@section('js')
<script src="<?= asset('js/autocomplete.js') ?>"></script>
<script src="<?= asset('js/participant/form.js') ?>"></script>
@endsection
@section('content')
<form action="{{ url()->current() }}" method="POST" enctype="multipart/form-data">
    <div class="d-flex justify-content-between mb-3">
        <x-button.back url="tournament/{{ $tournament_slug }}/group/{{ $group->slug }}" />
    </div>
    <x-alert />
    <div class="row">
        <div class="col-md-8">
            @csrf
            <div class="mb-3">
                <label for="team" class="form-label">Tim
                    <x-required />
                </label>
                <input type="text" class="form-control" id="team" name="team" class="@error('team') is-invalid @enderror" value="{{ old('team') }}" autocomplete="off">
                @error('team')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="participant" class="form-label">Peserta
                    <x-required />
                </label>
                <input type="text" class="form-control" id="participant" name="participant" class="@error('participant') is-invalid @enderror" value="{{ old('participant') }}" disabled="disabled" autocomplete="off">
                @error('participant')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
    <x-button.submit />
</form>
@endsection