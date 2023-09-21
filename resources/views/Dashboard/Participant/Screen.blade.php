<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Peserta Turnamen</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="<?= asset('vendors/bootstrap/bootstrap.min.css') ?>">
    <style>
        /* Tambahkan CSS tambahan jika diperlukan */
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1>{{strtoupper($data->tournament)}}</h1>
        <h5>{{strtoupper($data->group)}}</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Peserta</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participant as $p)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$p->member}}</td>
                    <td>{{$p->time ? $p->time : '00:00'}}</td>
                </tr>
                @endforeach
                <!-- Anda dapat menambahkan peserta lainnya sesuai kebutuhan -->
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS (opsional) -->
    <script src="<?= asset('vendors/bootstrap/bootstrap.min.js') ?>"></script>
</body>

</html>