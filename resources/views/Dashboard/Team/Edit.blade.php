@extends('Dashboard.Layout.Main')
@section('css')
    <link rel="stylesheet" href="<?= asset('css/upload_image.css') ?>">
@endsection


@section('content')
    <form action="{{ url()->current() }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="d-flex justify-content-between mb-3">
            <x-button.back url="team/{{ $data->slug }}" />
        </div>

        <x-alert />
        <div class="row">
            <div class="col-md-8">
                @csrf
                <div class="mb-3">
                    <label for="team" class="form-label">Tim
                        <x-required />
                    </label>
                    <input type="text" class="form-control" id="team" name="team"
                        class="@error('team') is-invalid @enderror" value="{{ old('team', $data->team) }}">
                    @error('team')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="team_initial" class="form-label">Kode Tim
                        <x-required />
                    </label>
                    <input type="text" class="form-control" id="team_initial" name="team_initial"
                        class="@error('team_initial') is-invalid @enderror"
                        value="{{ old('team_initial', $data->team_initial) }}">
                    @error('team_initial')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat
                        <x-required />
                    </label>
                    <input type="text" class="form-control" id="address" name="address"
                        class="@error('address') is-invalid @enderror" value="{{ old('address', $data->address) }}">
                    @error('address')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="coach" class="form-label">Pelatih
                        <x-required />
                    </label>
                    <input type="text" class="form-control" id="coach" name="coach"
                        class="@error('coach') is-invalid @enderror" value="{{ old('coach', $data->coach) }}">
                    @error('coach')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email
                        <x-required />
                    </label>
                    <input type="email" class="form-control" id="email" name="email"
                        class="@error('email') is-invalid @enderror" value="{{ old('email', $data->email) }}">
                    @error('email')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="web" class="form-label">URL Website Resmi</label>
                    <input type="text" class="form-control" id="web" name="web" placeholder="https://"
                        class="@error('web') is-invalid @enderror" value="{{ old('web', $data->website) }}">
                    @error('web')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label for="contact_name" class="form-label">Nama Kontak
                                <x-required />
                            </label>
                        </div>
                        <div class="col-6 mb-2">
                            <label for="phone" class="form-label">No HP
                                <x-required />
                            </label>
                        </div>
                        @foreach ($contact as $k => $c)
                            <div class="col-6 mb-2">
                                <input type="text" class="form-control" id="contact_name"
                                    name="contact[{{ $k }}][name]"
                                    class="@error('contact.$k.name') is-invalid @enderror"
                                    value="{{ old('contact.' . $k . '.name', $c->name) }}">
                                @error('contact.*.name')
                                    <small class="text-danger ms-2">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-6 mb-2">
                                <input type="number" class="form-control" id="phone"
                                    name="contact[{{ $k }}][phone]"
                                    class="@error('contact.$k.phone') is-invalid @enderror"
                                    value="{{ old('contact.' . $k . '.phone', $c->phone) }}">
                                @error('contact.*.phone')
                                    <small class="text-danger ms-2">{{ $message }}</small>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="{{ asset('storage/image/teams/' . $data->image) }}" alt="logo"
                    class="img-fluid rounded-circle img-preview" />
                <div class=" mb-3">
                    <label for="image" class="form-label">Logo</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*"
                        onchange="previewImg()" />
                    @error('image')
                        <small class="text-danger ms-2">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <x-button.submit />
        </div>
    </form>
@endsection

@section('js')
    <script src="<?= asset('js/upload_image.js') ?>"></script>
    <script>
        document.querySelector('#team').addEventListener('keyup', function() {
            // set initial team
            const namaTim = this.value;

            // Buat inisial tim
            const inisialTim = generateInitials(namaTim);

            // Tampilkan inisial pada elemen yang diinginkan
            document.querySelector('#team_initial').textContent = inisialTim;
        });

        function generateInitials(namaTim) {
            // Split nama tim menjadi kata-kata
            const words = namaTim.split(' ');

            // Buat inisial dari karakter pertama setiap kata
            const inisial = words.map(word => word.charAt(0)).join('');

            return inisial;
        }
    </script>
@endsection
