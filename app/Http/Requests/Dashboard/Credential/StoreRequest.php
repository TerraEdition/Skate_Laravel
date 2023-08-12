<?php

namespace App\Http\Requests\Dashboard\Credential;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::id() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'surat_permohonan' => 'uploaded|mimes:pdf|max:2048',
            'ijazah' => 'uploaded|mimes:pdf|max:2048',
            'str' => 'uploaded|mimes:pdf|max:2048',
            'sipp' => 'uploaded|mimes:pdf|max:2048',
            'sertifikat_pelatihan' => 'uploaded|mimes:pdf|max:2048',
            'daftar_riwayat_hidup' => 'uploaded|mimes:pdf|max:2048',
            'portofolio' => 'uploaded|mimes:pdf|max:2048',
            'surat_keterangan_sehat' => 'uploaded|mimes:pdf|max:2048',
            'pas_foto' => 'uploaded|image|mimes:jpg,png,jpeg|max:2048',
            'permohonan_kompetensi_sertifikasi' => 'uploaded|mimes:pdf|max:2048',
            'asesmen_mandiri' => 'uploaded|mimes:pdf|max:2048',
        ];
    }
    public function attributes()
    {
        return [
            //
        ];
    }
}
