@extends('Dashboard.Layout.Main')
@section('content')
    <div class="container pt-3">
        <x-alert />
        <div class="text-center">
            Selamat Datang
        </div>
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ url()->current() }}/import" method="POST" enctype="multipart/form-data">
                                @csrf
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
                            <a href="{{ url()->current() }}/export" target="_blank" class="text-decoration-none">
                                <small class="pb-2 mb-4 text-danger border-bottom border-danger">
                                    Jika belum ada formulir, maka bisa didownload disini
                                </small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
