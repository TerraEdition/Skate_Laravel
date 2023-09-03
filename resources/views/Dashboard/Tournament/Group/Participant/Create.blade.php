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
                    <select name="team" id="team" class="form-control @error('team') is-invalid @enderror">
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team') == $team->id ? 'selected' : '' }}>
                                {{ $team->team }}</option>
                        @endforeach
                    </select>
                    @error('team')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="participant" class="form-label">Peserta
                        <x-required />
                    </label>
                    <select name="member_id" id="member_id" class="form-control @error('member_id') is-invalid @enderror">
                        @foreach ($members_team as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->member }}</option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>
        <x-button.submit />
    </form>
@endsection
