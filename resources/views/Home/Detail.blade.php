@extends('Home.Layout.Main')
@section('content')
    <div class="d-flex justify-content-between py-3 px-2">
        <a class="btn btn-sm btn-danger" href="{{ url('') }}">Kembali</a>
        <div class="text-light fw-bold">{{ ucwords($group->group) }}</div>
    </div>
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
