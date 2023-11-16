<div class="row">
    <div class="col-6">
        <div class="alert alert-danger">
            Catatan : Jika sudah melakukan pengaturan group ini maka peserta tidak bisa mendaftar lagi
        </div>
        <form action="{{ url()->current() }}" method="POST">
            @csrf
            <div class="mb-3 mt-4">
                <label for="total_seat" class="form-label">Jumlah Seat
                    <x-required />
                </label>
                <input type="number" class="form-control" min="1" id="total_seat" name="total_seat" class="@error('total_seat') is-invalid @enderror" value="{{ old('total_seat', $data['total_seat'] ?? '1') }}">
                @error('total_seat')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="passes_position" class="form-label">Posisi teratas yang lolos
                    <x-required />
                </label>
                <input type="number" class="form-control" min="1" id="passes_position" name="passes_position" class="@error('passes_position') is-invalid @enderror" value="{{ old('passes_position', $data['passes_position'] ?? '1') }}">
                @error('passes_position')
                <small class="text-danger ms-2">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-flex gap-2">
                <x-button.back url="participant/{{ $tournament_slug }}/{{ $group->slug }}" />
                <x-button.submit />
            </div>
        </form>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                Info Group
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>Grup</td>
                        <td>:</td>
                        <td>{{ $group->group }}</td>
                    </tr>
                    <tr>
                        <td>Kelompok</td>
                        <td>:</td>
                        <td>{{ Convert::gender($group->gender, false) }}</td>
                    </tr>
                    <tr>
                        <td>Tahun Lahir</td>
                        <td>:</td>
                        <td>{{ $group->min_age }} - {{ $group->max_age }}</td>
                    </tr>
                    <tr>
                        <td>Peserta Terdaftar</td>
                        <td>:</td>
                        <td>{{ $group->total_participant }} </td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
</div>