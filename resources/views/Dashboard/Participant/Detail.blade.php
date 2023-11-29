@php
    use App\Helpers\Model;
@endphp
@extends('Dashboard.Layout.Main')
@section('content')
    <x-alert />
    <div class="d-flex gap-2">
        <x-button.back url="participant" />
        <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false" id="mode_title">
                Export
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ url()->current() }}/export-excel" target="_blank"><i
                            class="fa-solid fa-file-excel"></i> Export Excel</a></li>
                <li><a class="dropdown-item" href="{{ url()->current() }}/export-pdf" target="_blank"><i
                            class="fa-solid fa-file-pdf"></i> Export Pdf</a></li>
            </ul>
        </div>
        @if ($group->status < 2)
            <div>
                <div class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="fa-solid fa-upload"></i>
                    Import Excel
                </div>
                <div class="modal fade" id="registerModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Hasil Turnamen</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <small class="text-danger">* Harap Cek ulang waktu peserta kembali, karena ini akan
                                    mengganti
                                    data waktu yang telah di input sebelumnya jika ada</small>
                                <form action="{{ url()->current() }}/import-excel" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="group_slug" value="{{ $group->slug }}">
                                    <div class="mb-3">
                                        <label for="excel" class="form-label">Upload File Hasil Turnamen</label>
                                        <input class="form-control" type="file" id="excel" name="excel"
                                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                        @error('excel')
                                            <small class="text-danger ms-2">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <button class="btn btn-primary btn-sm form-control" type="submit">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="d-flex justify-content-between mt-3">
        <div class="fs-5"> Peserta Turnamen <b>{{ $group->tournament }}</b> di Grup <b>{{ $group->group }}</b></div>
        <div>
            @if (empty($setting_group))
                <a href="{{ url()->current() }}/setting-group" class="btn btn-outline-success">
                    Pengaturan Grup
                </a>
            @elseif ($group->status < 2)
                @php
                    $final_url = url()->current() . '/competition';
                    if ($group->round > 1) {
                        $final_url .= '/final';
                    }
                @endphp

                <a href="{{ $final_url }}" class="btn btn-outline-primary">
                    {{ $group->status == 0 ? 'Mulai Pertandingan' : 'Lanjutkan Pertandingan' }}
                </a>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        @if ($group->round <= 1 && $group->total_seat == 1)
            <table class="table" id="filter-search">
                <tr>
                    <th>#</th>
                    <th>No BIB</th>
                    <th>Nama</th>
                    <th>Tim</th>
                    <th>Waktu</th>
                    @if ($group->status == 2)
                        <th>Posisi</th>
                    @else
                        <th>Heat</th>
                    @endif
                </tr>
                @foreach ($participant as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->no_participant }}</td>
                        <td>{{ $p->member }}</td>
                        <td>{{ $p->team }}</td>
                        <td>{{ $p->time ?? '00:00' }}</td>
                        @if ($group->status == 2)
                            <td>{{ $loop->iteration }}</td>
                        @else
                            <td>{{ $p->seat }}</td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @else
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @for ($i = 0; $i < $group->total_seat; $i++)
                    <li class="nav-item" role="presentation">
                        <button @class(['nav-link active' => $i == 0, 'nav-link' => $i > 0]) id="seat{{ $i }}-tab" data-bs-toggle="tab"
                            data-bs-target="#seat{{ $i }}-tab-pane" type="button" role="tab"
                            aria-controls="seat{{ $i }}-tab-pane" aria-selected="true">Heat
                            {{ $i + 1 }}</button>
                    </li>
                @endfor
                <li class="nav-item" role="presentation">
                    <button class="nav-link " id="final-tab" data-bs-toggle="tab" data-bs-target="#final-tab-pane"
                        type="button" role="tab" aria-controls="final-tab-pane" aria-selected="true">Final
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                @for ($i = 0; $i < $group->total_seat; $i++)
                    <div @class([
                        'tab-pane fade show active' => $i == 0,
                        'tab-pane fade' => $i > 0,
                    ]) id="seat{{ $i }}-tab-pane" role="tabpanel"
                        aria-labelledby="seat{{ $i }}-tab" tabindex="0">
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
                                    $participant_perseat = Model::ParticipantPerSeat($group->slug, $i + 1);
                                @endphp
                                @foreach ($participant_perseat as $k => $p)
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
                @endfor
                <div class="tab-pane fade" id="final-tab-pane" role="tabpanel" aria-labelledby="final-tab" tabindex="0">
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
                                $participant_final = Model::ParticipantPerFinal($group->slug);
                            @endphp
                            @foreach ($participant_final as $k => $p)
                                <tr @class(['bg-primary text-light' => $k < $group->passes])>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->no_participant }}</td>
                                    <td>{{ strtoupper($p->member) }}</td>
                                    <td>{{ strtoupper($p->team) }}</td>
                                    <td>{{ $p->time ?? '00:00' }}</td>
                                    <td>{{ $loop->iteration }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
