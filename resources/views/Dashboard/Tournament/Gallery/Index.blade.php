@extends('Dashboard.Layout.Main')
@section('css')
    <link rel="stylesheet" href="<?= asset('css/upload_image.css') ?>">
@endsection
@section('content')
    <div class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
        <i class="fa-solid fa-upload"></i>
        Unggah Berkas
    </div>
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Unggah</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ url()->current() }}/upload" method="POST" enctype="multipart/form-data">
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
                </div>
            </div>
        </div>
    </div>
    @foreach ($gallery as $g)
    @endforeach
@endsection

@section('js')
    <script src="https://cdn.tiny.cloud/1/plrqcl0e028uwokisqjivacjlga369r1mz1qrwwahy900kf1/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="<?= asset('js/upload_image.js') ?>"></script>
    <script src="<?= asset('js/tinymce.js') ?>"></script>
@endsection
