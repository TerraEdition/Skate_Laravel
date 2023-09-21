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
    <div id="screen-main">
        <h3>Peserta : <span id="participant_name">-</span></h3>
        <div>Time</div>
        <div id="show_time">00:00</div>
    </div>
    <div class="container mt-5" id="screen-sub">
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
<script>
    const show_time = document.getElementById("show_time")
    const participant_name = document.getElementById("participant_name")
    let milliseconds = 0
    window.addEventListener('message', function(event) {
        // Memeriksa sumber pesan
        if (event.data.message == "Start") {
            startTime = setInterval(update_time, 10)
        } else if (event.data.message == "Stop") {
            stop_time()
        } else if (event.data.message == "Participant") {
            participant_name.textContent = event.data.value
        } else {
            stop_time()
            show_time.textContent = event.data.value
        }
    }, false);
    // // Mengirim pesan ke jendela utama
    // window.opener.postMessage('Halo dari jendela anak!', '*');

    function update_time() {
        milliseconds += 10
        let minutes = Math.floor(milliseconds / 60000);
        let seconds = Math.floor((milliseconds % 60000) / 1000);
        let miliseconds = (milliseconds % 1000)

        show_time.textContent =
            (minutes < 10 ? "0" : "") + minutes + ":" +
            (seconds < 10 ? "0" : "") + seconds + ":" +
            (miliseconds < 100 ? "0" : "") + (miliseconds < 10 ? "0" : "") + miliseconds;
    }
    async function stop_time() {
        clearInterval(startTime);
    }
</script>