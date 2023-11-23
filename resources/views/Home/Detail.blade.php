@extends('Home.Layout.Main')
@section('content')
    <div class="d-flex justify-content-between py-3 px-2">
        <a class="btn btn-sm btn-danger" href="{{ url('') }}">Kembali</a>
        <div class="text-light fw-bold">{{ ucwords($group->group) }}</div>
    </div>
    @if ($group->round <= 1 && $group->total_seat == 1)
        <div class="table-responsive">
            <table class="table table-striped table-primary">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. BIB</th>
                        <th>Peserta</th>
                        <th>Tim</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody id='display_data'>
                </tbody>
            </table>
        </div>
    @else
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @for ($i = 0; $i < $group->total_seat; $i++)
                <li class="nav-item" role="presentation">
                    <button @class([
                        'nav-link active text-dark' => $i == 0,
                        'nav-link text-dark' => $i > 0,
                    ]) id="seat{{ $i }}-tab" data-bs-toggle="tab"
                        onclick="change_seat({{ $i }})" data-bs-target="#seat{{ $i }}-tab-pane"
                        type="button" role="tab" aria-controls="seat{{ $i }}-tab-pane"
                        aria-selected="true">Heat
                        {{ $i + 1 }}</button>
                </li>
            @endfor
            <li class="nav-item" role="presentation">
                <button class="nav-link text-dark" id="final-tab" onclick="change_seat({{ $i + 1 }})"
                    data-bs-toggle="tab" data-bs-target="#final-tab-pane" type="button" role="tab"
                    aria-controls="final-tab-pane" aria-selected="true">Final
                </button>
            </li>
        </ul>
        <div id="display_data"></div>
    @endif
@endsection
@section('js')
    <script>
        let index_seat = 0;
        let timeoutId;

        function change_seat(index) {
            index_seat = index;
            clearTimeout(timeoutId);
            load_data();
        }
        load_data();
        async function load_data() {
            const response = await fetch(base_url + '/api/get-live-score/{{ $group->id }}?index=' + index_seat)
                .then(res => res.json());
            document.querySelector('#display_data').innerHTML = response.data.html;
            timeoutId = setTimeout(() => {
                load_data();
            }, 2000);
        }
    </script>
@endsection
