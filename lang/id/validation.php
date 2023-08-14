<?php

return [
    /*
    |---------------------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi
    |---------------------------------------------------------------------------------------
    |
    | Baris bahasa berikut ini berisi standar pesan kesalahan yang digunakan oleh
    | kelas validasi. Beberapa aturan mempunyai banyak versi seperti aturan 'size'.
    | Jangan ragu untuk mengoptimalkan setiap pesan yang ada di sini.
    |
    */

    'accepted'        => 'Kolom :attribute harus diterima.',
    'active_url'      => 'Kolom :attribute bukan URL yang valid.',
    'after'           => 'Kolom :attribute harus berisi tanggal setelah :date.',
    'after_or_equal'  => 'Kolom :attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha'           => 'Kolom :attribute hanya boleh berisi huruf.',
    'alpha_dash'      => 'Kolom :attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'       => 'Kolom :attribute hanya boleh berisi huruf dan angka.',
    'array'           => 'Kolom :attribute harus berisi sebuah array.',
    'before'          => 'Kolom :attribute harus berisi tanggal sebelum :date.',
    'before_or_equal' => 'Kolom :attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between'         => [
        'numeric' => 'Kolom :attribute harus bernilai antara :min sampai :max.',
        'file'    => 'Kolom :attribute harus berukuran antara :min sampai :max kilobita.',
        'string'  => 'Kolom :attribute harus berisi antara :min sampai :max karakter.',
        'array'   => 'Kolom :attribute harus memiliki :min sampai :max anggota.',
    ],
    'boolean'        => 'Kolom :attribute harus bernilai true atau false',
    'confirmed'      => 'Konfirmasi Kolom :attribute tidak cocok.',
    'date'           => 'Kolom :attribute bukan tanggal yang valid.',
    'date_equals'    => 'Kolom :attribute harus berisi tanggal yang sama dengan :date.',
    'date_format'    => 'Kolom :attribute tidak cocok dengan format :format.',
    'different'      => 'Kolom :attribute dan :other harus berbeda.',
    'digits'         => 'Kolom :attribute harus terdiri dari :digits angka.',
    'digits_between' => 'Kolom :attribute harus terdiri dari :min sampai :max angka.',
    'dimensions'     => 'Kolom :attribute tidak memiliki dimensi gambar yang valid.',
    'distinct'       => 'Kolom :attribute memiliki nilai yang duplikat.',
    'email'          => 'Kolom :attribute harus berupa alamat surel yang valid.',
    'ends_with'      => 'Kolom :attribute harus diakhiri salah satu dari berikut: :values',
    'exists'         => 'Kolom :attribute yang dipilih tidak valid.',
    'file'           => 'Kolom :attribute harus berupa sebuah berkas.',
    'filled'         => 'Kolom :attribute harus memiliki nilai.',
    'gt'             => [
        'numeric' => 'Kolom :attribute harus bernilai lebih besar dari :value.',
        'file'    => 'Kolom :attribute harus berukuran lebih besar dari :value kilobita.',
        'string'  => 'Kolom :attribute harus berisi lebih besar dari :value karakter.',
        'array'   => 'Kolom :attribute harus memiliki lebih dari :value anggota.',
    ],
    'gte' => [
        'numeric' => 'Kolom :attribute harus bernilai lebih besar dari atau sama dengan :value.',
        'file'    => 'Kolom :attribute harus berukuran lebih besar dari atau sama dengan :value kilobita.',
        'string'  => 'Kolom :attribute harus berisi lebih besar dari atau sama dengan :value karakter.',
        'array'   => 'Kolom :attribute harus terdiri dari :value anggota atau lebih.',
    ],
    'image'    => 'Kolom :attribute harus berupa gambar.',
    'in'       => 'Kolom :attribute yang dipilih tidak valid.',
    'in_array' => 'Kolom :attribute tidak ada di dalam :other.',
    'integer'  => 'Kolom :attribute harus berupa bilangan bulat.',
    'ip'       => 'Kolom :attribute harus berupa alamat IP yang valid.',
    'ipv4'     => 'Kolom :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'     => 'Kolom :attribute harus berupa alamat IPv6 yang valid.',
    'json'     => 'Kolom :attribute harus berupa JSON string yang valid.',
    'lt'       => [
        'numeric' => 'Kolom :attribute harus bernilai kurang dari :value.',
        'file'    => 'Kolom :attribute harus berukuran kurang dari :value kilobita.',
        'string'  => 'Kolom :attribute harus berisi kurang dari :value karakter.',
        'array'   => 'Kolom :attribute harus memiliki kurang dari :value anggota.',
    ],
    'lte' => [
        'numeric' => 'Kolom :attribute harus bernilai kurang dari atau sama dengan :value.',
        'file'    => 'Kolom :attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
        'string'  => 'Kolom :attribute harus berisi kurang dari atau sama dengan :value karakter.',
        'array'   => 'Kolom :attribute harus tidak lebih dari :value anggota.',
    ],
    'max' => [
        'numeric' => 'Kolom :attribute maksimal bernilai :max.',
        'file'    => 'Kolom :attribute maksimal berukuran :max kilobita.',
        'string'  => 'Kolom :attribute maksimal berisi :max karakter.',
        'array'   => 'Kolom :attribute maksimal terdiri dari :max anggota.',
    ],
    'mimes'     => 'Kolom :attribute harus berupa berkas berjenis: :values.',
    'mimetypes' => 'Kolom :attribute harus berupa berkas berjenis: :values.',
    'min'       => [
        'numeric' => 'Kolom :attribute minimal bernilai :min.',
        'file'    => 'Kolom :attribute minimal berukuran :min kilobita.',
        'string'  => 'Kolom :attribute minimal berisi :min karakter.',
        'array'   => 'Kolom :attribute minimal terdiri dari :min anggota.',
    ],
    'not_in'               => 'Kolom :attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format Kolom :attribute tidak valid.',
    'numeric'              => 'Kolom :attribute harus berupa angka.',
    'password'             => 'Kata sandi salah.',
    'present'              => 'Kolom :attribute wajib ada.',
    'regex'                => 'Format Kolom :attribute tidak valid.',
    'required'             => 'Kolom :attribute wajib diisi.',
    'required_if'          => 'Kolom :attribute wajib diisi bila :other adalah :value.',
    'required_unless'      => 'Kolom :attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with'        => 'Kolom :attribute wajib diisi bila terdapat :values.',
    'required_with_all'    => 'Kolom :attribute wajib diisi bila terdapat :values.',
    'required_without'     => 'Kolom :attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => 'Kolom :attribute wajib diisi bila sama sekali tidak terdapat :values.',
    'same'                 => 'Kolom :attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => 'Kolom :attribute harus berukuran :size.',
        'file'    => 'Kolom :attribute harus berukuran :size kilobyte.',
        'string'  => 'Kolom :attribute harus berukuran :size karakter.',
        'array'   => 'Kolom :attribute harus mengandung :size anggota.',
    ],
    'starts_with' => 'Kolom :attribute harus diawali salah satu dari berikut: :values',
    'string'      => 'Kolom :attribute harus berupa string.',
    'timezone'    => 'Kolom :attribute harus berisi zona waktu yang valid.',
    'unique'      => 'Kolom :attribute sudah ada sebelumnya.',
    'unique_void' => 'Kolom :attribute sudah ada sebelumnya.',
    'uploaded'    => 'Kolom :attribute gagal diunggah.',
    'url'         => 'Format Kolom :attribute tidak valid.',
    'uuid'        => 'Kolom :attribute harus merupakan UUID yang valid.',

    /*
    |---------------------------------------------------------------------------------------
    | Baris Bahasa untuk Validasi Kustom
    |---------------------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi untuk atribut sesuai keinginan dengan menggunakan
    | konvensi Kolom "attribute.rule" dalam penamaan barisnya. Hal ini mempercepat dalam menentukan
    | baris bahasa kustom yang spesifik untuk aturan atribut yang diberikan.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |---------------------------------------------------------------------------------------
    | Kustom Validasi Atribut
    |---------------------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk menukar 'placeholder' atribut dengan sesuatu yang
    | lebih mudah dimengerti oleh pembaca seperti "Alamat Surel" daripada "surel" saja.
    | Hal ini membantu kita dalam membuat pesan menjadi lebih ekspresif.
    |
    */

    'attributes' => [],
];
