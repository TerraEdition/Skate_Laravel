<div class="alert alert-danger">
    Catatan : Jika sudah melakukan pengaturan group ini maka peserta tidak bisa mendaftar lagi
</div>
<form action="{{ url()->current() }}" method="POST">
    @csrf
    <div class="mb-3 mt-4">
        <label for="total_seat" class="form-label">Jumlah Seat
            <x-required />
        </label>
        <input type="number" class="form-control" min="1" id="total_seat" name="total_seat"
            class="@error('total_seat') is-invalid @enderror"
            value="{{ old('total_seat', $data['total_seat'] ?? '1') }}">
        @error('total_seat')
            <small class="text-danger ms-2">{{ $message }}</small>
        @enderror
    </div>
    <div class="mb-3">
        <label for="passes_position" class="form-label">Posisi teratas yang lolos
            <x-required />
        </label>
        <input type="number" class="form-control" min="1" id="passes_position" name="passes_position"
            class="@error('passes_position') is-invalid @enderror"
            value="{{ old('passes_position', $data['passes_position'] ?? '1') }}">
        @error('passes_position')
            <small class="text-danger ms-2">{{ $message }}</small>
        @enderror
    </div>
    <x-button.submit />
</form>
