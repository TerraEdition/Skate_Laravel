@extends('Dashboard.Layout.Main')
@section('content')
    <div class="d-flex gap-2 mb-3">
        <x-button.edit url="team/edit/{{ $data->slug }}" />
        <x-button.back url="team" />
        @if (!empty($near_tournament))
            <div>
                <div class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="fa-solid fa-upload"></i>
                    Daftar Via Excel
                </div>
                <div class="modal fade" id="registerModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5">Pendaftaran Turnamen</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url()->current() }}/register-tournament" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="slug" value="{{ $data->slug }}">
                                    <div class="mb-3">
                                        <label for="excel" class="form-label">Upload File Pendaftaran</label>
                                        <input class="form-control" type="file" id="excel" name="excel">
                                        @error('excel')
                                            <small class="text-danger ms-2">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <button class="btn btn-primary btn-sm form-control" type="submit">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        Simpan
                                    </button>
                                </form>
                                <a href="{{ url('') }}/tournament/{{ $near_tournament->slug }}/export/{{ $data->slug }}"
                                    class="text-decoration-none">
                                    <small class="pb-2 mb-4 text-danger border-bottom border-danger">
                                        Jika belum ada formulir, maka bisa didownload disini
                                    </small>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <x-alert />
    <div class="h4 pb-2 my-3 text-dark border-bottom border-dark">
        <h3>{{ $data->team }}</h3>
    </div>
    <div class="row mb-4">
        <div class="col-md-8">
            <h5>Data Tim</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td>Pelatih</td>
                        <td>:</td>
                        <td> {{ $data->coach }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>:</td>
                        <td> {{ $data->address }}</td>
                    </tr>
                    <tr>
                        <td>No HP</td>
                        <td>:</td>
                        <td>{{ $data->phone }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>:</td>
                        <td> <a href="mailto:{{ $data->email }}">{{ $data->email }}</a></td>
                    </tr>
                    @if ($data->website)
                        <tr>
                            <td>Website</td>
                            <td>:</td>
                            <td>
                                <a href="{{ $data->website }}" target="_blank">Official</a>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <img src="{{ asset('storage/image/teams/' . $data->image) }}" alt="logo"
                class="img-fluid w-75 rounded-circle" />
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <h5>Data Anggota</h5>
        <x-button.create url="team/{{ $data->slug }}/member" label="Tambah Anggota" />
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Anggota</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Umur</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($member as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->member }}</td>
                        <td>{{ Convert::gender($r->gender, false) }}</td>
                        <td>{{ Date::format_long($r->birth) }}</td>
                        <td>{{ Date::calculate_age($r->birth) }} Tahun</td>
                        <td>
                            <div class="d-flex gap-2">
                                <x-button.detail url="team/{{ $data->slug }}/member/{{ $r->slug }}" />
                                <x-button.delete url="team/{{ $data->slug }}/member/{{ $r->slug }}" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $member->links('Paginate.Custom') }}
    </div>
@endsection
