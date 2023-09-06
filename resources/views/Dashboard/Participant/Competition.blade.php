@extends('Dashboard.Layout.Main')
@section('content')

<x-alert />
<x-button.back url="participant" />
<div class="d-flex justify-content-between mt-3">
    <div class="fs-5"> Peserta Turnamen <b>{{$group->tournament}}</b> di Grup <b>{{$group->group}}</b></div>
    @if ($group->status==1)
    <a href="{{url()->current()}}/close" class="btn btn-outline-primary">Tutup Pertandingan Grup Ini</a>
    @endif
</div>

<div class="table-responsive">
    <table class="table">
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Tim</th>
            <th>Waktu</th>
            <th>Aksi</th>
        </tr>
        @foreach ($participant as $p)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$p->member}}</td>
            <td>{{$p->team}}</td>
            <td id="time_participant{{$p->participant_id}}">{{$p->time ?? '00:00:000'}}</td>
            <td>
                <div data-participant_id="{{$p->participant_id}}" class="btn btn-sm btn-primary" id="show_stopwatch">
                    Mulai</div>
            </td>
        </tr>
        @endforeach
    </table>
</div>

<div class="modal fade" id="timeCompetition" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="timeCompetitionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="timeCompetitionLabel">Stopwatch</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <h1 id="show_time">00:00:000</h1>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="start_time">Mulai</button>
                <button type="button" class="btn btn-danger d-none" id="finish_time" data-participant_id="">Selesai</button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
    const show_modal_btn = document.querySelectorAll("#show_stopwatch")
    const start_btn = document.querySelector("#start_time")
    const finish_btn = document.querySelector("#finish_time")
    const show_time = document.getElementById("show_time")
    let milliseconds = 0
    const modal_stopwatch = new bootstrap.Modal('#timeCompetition')

    show_modal_btn.forEach(btn => {
        btn.addEventListener('click', function() {
            finish_btn.setAttribute('data-participant_id', this.dataset.participant_id)
            modal_stopwatch.show()
        })
    })
    start_btn.addEventListener('click', function() {
        toggle_btn()
        milliseconds = 0
        startTime = setInterval(update_time, 10)
    })

    finish_btn.addEventListener('click', function() {
        participant_id = this.dataset.participant_id;
        toggle_btn()
        stop_time(participant_id);
    })

    function toggle_btn() {
        finish_btn.classList.toggle('d-none');
        start_btn.classList.toggle('d-none');
    }

    function update_time() {
        milliseconds += 10
        let minutes = Math.floor(milliseconds / 60000);
        let seconds = Math.floor((milliseconds % 60000) / 1000);
        let miliseconds = (milliseconds % 1000)

        show_time.textContent =
            (minutes < 10 ? "0" : "") + minutes + ":" +
            (seconds < 10 ? "0" : "") + seconds + ":" +
            (miliseconds < 100 ? "0" : "") + (miliseconds < 10 ? "0" : "") + miliseconds;
    }

    async function stop_time(participant_id) {
        clearInterval(startTime);
        modal_stopwatch.hide()
        data = await save_time_participant(participant_id);
        if (data.status) {
            document.querySelector("#time_participant" + participant_id).textContent = show_time.textContent
            // document.querySelector(`#show_stopwatch[data-participant_id="${participant_id}"]`).remove()
            show_time.textContent = "00:00:000";
        } else {
            alert(data.message)
        }
    }

    async function save_time_participant(participant_id) {
        try {
            const response = await fetch(base_url + '/api/participant/save-time?participant_id=' + participant_id +
                    '&time=' +
                    show_time.textContent)
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