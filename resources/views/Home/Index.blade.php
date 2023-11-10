@extends('Home.Layout.Main')
@section('content')
<div class="modal fade" id="firstModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="firstModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-primary">
            <img src="{{ asset('storage/screen/1.jpeg') }}" alt="" class="img-fluid">
        </div>
    </div>
</div>
<x-alert />
<div class="text-light py-3 px-2">
    Pertandingan yang dipertandingkan :
</div>
<table class="table table-striped table-primary">
    <thead>
        <tr>
            <th>No.</th>
            <th>Kelas / Grup</th>
            <th>Total Peserta</th>
        </tr>
    </thead>
    @foreach ($group as $t)
    <tr class="group-info" data-slug="{{ $t->slug }}">
        <td>{{ $loop->iteration }}</td>
        <td>{{ ucwords($t->group) }}</td>
        <td>{{ $t->total_participant }} Peserta</td>
    </tr>
    @endforeach
</table>

{{ $group->links('Paginate.Custom') }}
@endsection
@section('js')
<script>
    new bootstrap.Modal(document.getElementById('firstModal')).show();

    document.querySelectorAll(".group-info").forEach(element => {
        element.addEventListener('click', function() {
            location.replace(current_url + '/' + element.dataset.slug)
        });
    });
</script>
@endsection