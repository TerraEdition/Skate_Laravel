@extends('Dashboard.Layout.Main')
@section('css')
@endsection
@section('js')
@endsection

@section('content')
    <x-alert />
    @if (!empty($data))
        <div class="pb-2 my-3 text-dark border-bottom border-danger border-3">
            <h6 class="text-danger">Kesalahan Import Data : Tim {{ $data[0]['team'] }}</h6>
            <div>
                <div class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="fa-solid fa-upload"></i>
                    Upload Ulang
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
                                <form action="{{ Str::replaceLast('failed', '', url()->current()) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="team_slug" value="{{ $data[0]['team_slug'] }}">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <small>Mohon untuk perbaiki data dari file excel sebelumnya, lalu bisa melakukan import ulang.</small>
        <table class="table">
            <tr>
                <td>Baris</td>
                <td>Nama</td>
                <td>Tanggal Lahir</td>
                <td>Grup</td>
                <td>Pesan Kesalahan</td>
            </tr>
            @foreach ($data as $r)
                <tr>
                    <td>{{ $r['row'] }}</td>
                    <td>{{ $r['member'] }}</td>
                    <td>{{ $r['birth'] }}</td>
                    <td>{{ $r['group'] }}</td>
                    <td>{{ $r['msg'] }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <script>
            window.location.replace("{{ Str::replaceLast('register-tournament/failed', '', url()->current()) }}");
        </script>
    @endif
@endsection
