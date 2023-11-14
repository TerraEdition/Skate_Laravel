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
    <div class="fw-bold text-light ">
        <span class="text-decoration-underline"> {{$tournament->tournament}}</span>
        <i class=" fa-solid fa-circle-info" data-bs-toggle="modal" data-bs-target="#infoModal"></i>
    </div>
    <div class="modal fade" id="infoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <table class="table table-secondary">
                    <tr>
                        <td class="table-warning">{{$group->where('status', 1)->count()}}</td>
                        <td>Sedang Berjalan</td>
                    </tr>
                    <tr>
                        <td class="table-primary" width="50%">{{$group->where('status', 0)->count()}}</td>
                        <td>Belum di Mulai</td>
                    </tr>
                    <tr>
                        <td class="table-success">{{$group->where('status', 2)->count()}}</td>
                        <td>Selesai</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-primary">
        <thead>
            <tr>
                <th>No.</th>
                <th>Kelas / Grup</th>
                <th>Total Peserta</th>
            </tr>
        </thead>
        @foreach ($group as $t)
        <tr data-slug="{{ $t->slug }}" class="
                            {{ $t->status == 1 ? 'table-warning' : '' }}
                            {{ $t->status == 2 ? 'table-success' : '' }}
                            group-info">
            <td>{{ $loop->iteration }}</td>
            <td>{{ ucwords($t->group) }}</td>
            <td>{{ $t->total_participant }} Peserta</td>
        </tr>
        @endforeach
    </table>
</div>
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