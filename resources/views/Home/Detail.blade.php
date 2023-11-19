@extends('Home.Layout.Main')
@section('content')
    <div class="d-flex justify-content-between py-3 px-2">
        <a class="btn btn-sm btn-danger" href="{{ url('') }}">Kembali</a>
        <div class="text-light fw-bold">{{ ucwords($group->group) }}</div>
    </div>
    @if ($group->round > 1)
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @for ($i = 0; $i < $group->total_seat; $i++)
                <li class="nav-item" role="presentation">
                    <button @class([
                        'nav-link text-dark active' => $i == 0,
                        'nav-link text-dark' => $i > 0,
                    ]) id="seat{{ $i }}-tab" data-bs-toggle="tab"
                        data-bs-target="#seat{{ $i }}-tab-pane" type="button" role="tab"
                        aria-controls="seat{{ $i }}-tab-pane" aria-selected="true">Seat
                        {{ $i + 1 }}</button>
                </li>
            @endfor
            <li class="nav-item" role="presentation">
                <button class="nav-link " id="final-tab" data-bs-toggle="tab" data-bs-target="#final-tab-pane"
                    type="button" role="tab" aria-controls="final-tab-pane" aria-selected="true">Final
                </button>
            </li>
        </ul>
    @endif
    <div class="table-responsive">
        <table class="table table-striped table-primary">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. BIB</th>
                    <th>Kelas / Grup</th>
                    <th>Tim</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
@endsection
@section('js')
    <script>
        load_data();

        async function load_data() {
            const response = await fetch(base_url + '/api/get-live-score/{{ $group->id }}')
                .then(res => res.json());
            document.querySelector('tbody').innerHTML = response.data.html;
            setTimeout(() => {
                load_data();
            }, 2000);
        }
    </script>
@endsection
