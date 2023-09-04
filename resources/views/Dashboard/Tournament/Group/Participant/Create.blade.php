@extends('Dashboard.Layout.Main')
@section('css')
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
                <label for="team_slug" class="form-label">Tim
                    <x-required />
                </label>
                <input type="hidden" id="group_slug" value="{{$group->slug}}">
                <select name="team_slug" id="team_slug" class="form-control @error('team_slug') is-invalid @enderror">
                    @foreach ($teams as $team)
                    <option value="{{ $team->slug }}" {{ old('team') == $team->slug ? 'selected' : '' }}>
                        {{ $team->team }}
                    </option>
                    @endforeach
                </select>
                @error('team_slug')
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
                        {{ $member->member }}
                    </option>
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

@section('js')
<script>
    const team_input = document.querySelector("#team_slug");
    const member_input = document.querySelector("#member_id");
    const group_slug = document.querySelector("#group_slug");

    team_input.addEventListener('change', async function() {
        data = await search_member()
        if (data.status) {
            member_input.innerHTML = '';
            data.data.member.forEach(data => {
                const optionEl = document.createElement("option");
                optionEl.innerHTML = data.member;
                optionEl.value = data.id;
                member_input.appendChild(optionEl);
            });
        } else {
            alert(data.message)
        }
    })
    async function search_member() {
        try {
            const response = await fetch(base_url + '/api/team/member/search?group_slug=' + group_slug.value +
                    '&team_slug=' + team_input.value)
                .then(res => {
                    return res.json()
                })
            return response;
        } catch (error) {
            return "Error fetching data:", error;
        }
    }
</script>
@endsection