<form action="{{ url()->current() }}" method="POST">
    @csrf
    @php
        $total_seat = (int) $data['total_seat'][0]['data'];
        $start = 0;
        $offset = ceil($group->total_participant / $total_seat);
    @endphp
    @for ($i = 1; $i <= $total_seat; $i++)
        <div class="mb-3">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                data-bs-target="#seat{{ $i }}" aria-expanded="false">
                Seat {{ $i }}
            </button>
            <div class="collapse show" id="seat{{ $i }}">
                <div class="card mt-3">
                    <div class="card-body">
                        @for ($p = $start; $p < $offset; $p++)
                            @if (isset($participant[$p]))
                                <div class="input-group mb-3">
                                    <select id="seat" name="seat[{{ $i }}][]"
                                        data-index="{{ $p }}" class="form-control">
                                        @foreach ($participant as $pc)
                                            <option value="{{ $pc->id }}"
                                                data-team_initial="{{ $pc->team_initial }}"
                                                data-team="{{ $pc->team }}"
                                                {{ $participant[$p]->id == $pc->id ? 'selected' : '' }}>
                                                {{ $pc->member }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="input-group-text" id="team-text{{ $p }}"></span>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
                @php
                    $start = $p;
                    $offset += $offset;
                @endphp
            </div>
        </div>
    @endfor
    <div class="d-flex gap-2">
        <a href="{{ url()->current() }}/back" class="btn btn-sm btn-danger">Reset dan Ulang</a>
        <x-button.submit />
    </div>
</form>

<script>
    const select_participan = document.querySelectorAll('#seat');
    let old_value = '';
    let old_team_initial = '';
    let old_team = '';
    select_participan.forEach(e => {
        e.addEventListener('click', function() {
            old_value = this.value;
            old_team_initial = this.options[this.selectedIndex].getAttribute(
                'data-team_initial');
            old_team = this.options[this.selectedIndex].getAttribute(
                'data-team');
        });

        e.addEventListener('change', function() {
            change_value_with_other_element(this);
        });

        // Perbarui teks tim untuk elemen saat pertama kali halaman dimuat
        const teamText = document.querySelector("#team-text" + e.dataset.index);
        teamText.innerHTML = e.options[e.selectedIndex].getAttribute('data-team_initial');
        teamText.setAttribute('title', e.options[e.selectedIndex].getAttribute('data-team'));
    });

    function change_value_with_other_element(selectedElement) {
        select_participan.forEach(a => {
            if (a !== selectedElement && a.value === selectedElement.value) {
                const aTeamInitial = a.options[a.selectedIndex].getAttribute('data-team_initial');
                const aTeam = a.options[a.selectedIndex].getAttribute('data-team');
                a.value = old_value;

                // Perbarui teks tim untuk elemen 'a'
                const teamTextA = document.querySelector("#team-text" + a.dataset.index);
                teamTextA.innerHTML = old_team_initial;
                teamTextA.setAttribute('title', old_team);

                // Perbarui teks tim untuk elemen yang dipilih
                const teamTextSelected = document.querySelector("#team-text" + selectedElement.dataset.index);
                teamTextSelected.innerHTML = aTeamInitial;
                teamTextSelected.setAttribute('title', aTeam);
            }
        });
    }
</script>
