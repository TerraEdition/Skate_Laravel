@php
    use App\Helpers\Model;
@endphp
@extends('Dashboard.Layout.Main')
@section('content')
    <x-alert />
    <div class="d-flex justify-content-between mt-3">
        <div class="fs-5"> Peserta Turnamen <b>{{ $group->tournament }}</b> di Grup <b>{{ $group->group }}</b></div>
    </div>
    <form action="{{ url()->current() }}" method="POST">
        <div class="d-flex gap-2">
            <x-button.back url="participant" />
            @csrf
            <button class="btn btn-sm btn-primary" type="submit">Simpan dan Lanjutkan ke Final</button>
        </div>
        <div class="container">
            @for ($i = 1; $i <= $group->total_seat; $i++)
                <div class="my-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="text-center">Heat {{ $i }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>#</th>
                                        <th>No BIB</th>
                                        <th>Nama</th>
                                        <th>Tim</th>
                                        <th>Waktu</th>
                                        <th>Posisi</th>
                                    </tr>
                                    @php
                                        $participant_perseat = Model::ParticipantPerSeat($group->slug, $i);
                                    @endphp
                                    @foreach ($participant_perseat as $k => $p)
                                        @if ($k < $group->passes)
                                            <input type="hidden" name="participant[]" value="{{ $p->participant_id }}">
                                        @endif
                                        <tr @class(['bg-primary text-light' => $k < $group->passes])>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $p->no_participant }}</td>
                                            <td>{{ $p->member }}</td>
                                            <td>{{ $p->team }}</td>
                                            <td>{{ $p->time ?? '00:00' }}</td>
                                            <td>{{ $loop->iteration }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </form>

@endsection
