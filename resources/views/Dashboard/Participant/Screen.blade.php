<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Peserta Turnamen</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="<?= asset('vendors/bootstrap/bootstrap.min.css') ?>">
    <style>
        body {
            background-color: #323eac;
            color: white;
            overflow: hidden;
        }

        div#screen {
            height: 100vh;
            width: 100vw;
            border: 10px solid white;
        }

        div#mini_screen {
            border: 5px solid white;
            position: absolute;
            bottom: 0;
            right: 0;
            width: 30vw;
            height: 100vh;
            overflow-y: auto;
            text-align: center;
            padding: 16px 32px;
            transition: 0.3s;
            background-color: #2b445c;
        }

        /* div#mini_screen:hover {
            width: 90vw;
            height: 80vh;
            transition: 0.5s;
        } */

        div#mini_screen.fullscreen {
            width: 100vw;
            height: 100vh;
            transition: 0.5s;
        }

        div#show_time {
            font-size: 15vh;
        }
    </style>
</head>

<body>
    <div id="screen" class="row pt-5">
        <div class="col-8">
            <div class="text-center my-5">
                <h4 id="participant_name"></h4>
                <h4 id="participant_number"></h4>
                <div id="show_time">00:00:000</div>
            </div>
            {{-- <img src="{{ asset('storage/screen/1.jpeg') }}" alt="" class="w-50 h-50"> --}}
        </div>
    </div>
    <div class="container" id="mini_screen">
    </div>

    <!-- Include Bootstrap JS (opsional) -->
    <script src="<?= asset('vendors/bootstrap/bootstrap.min.js') ?>"></script>
</body>

</html>
<script>
    let seat_now = 1
    const show_time = document.getElementById("show_time")
    const participant_name = document.getElementById("participant_name")
    const participant_number = document.getElementById("participant_number")
    const mini_screen = document.getElementById("mini_screen")
    let milliseconds = 0
    window.addEventListener('message', function(event) {
        if (event.data.message == "Set_Seat") {
            seat_now = event.data.value
            loadMiniScreen();
        } else if (event.data.message == "Start") {
            startTime = setInterval(update_time, 10)
        } else if (event.data.message == "Stop") {
            stop_time()
            show_time.textContent = event.data.value
            milliseconds = 0
            loadMiniScreen();
        } else if (event.data.message == "Participant") {
            participant_name.textContent = event.data.value
            show_time.textContent = '00:00:000'
        } else if (event.data.message == "Participant_Number") {
            participant_number.textContent = event.data.value
            show_time.textContent = '00:00:000'
        } else if (event.data.message == "Finish") {
            mini_screen.classList.add('fullscreen')
            loadMiniScreen()
        } else if (event.data.message == "Save") {
            show_time.textContent = event.data.value
        } else {
            alert(event.data.value)
            show_time.textContent = event.data.value
        }
    }, false);

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
        if (typeof startTime != 'undefined') {
            clearInterval(startTime)
        };
    }
    loadMiniScreen();
    // load mini screen
    async function loadMiniScreen() {
        try {
            mini_screen.innerHTML = `
            <div class="spinner-grow mt-5" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow mt-5" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow mt-5" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            `
            const response = await fetch(window.location.href + '/mini?seat=' + seat_now);
            const data = await response.json();
            if (data.status) {
                mini_screen.innerHTML = data.data
            }
        } catch (error) {
            console.log("Error fetching data:", error)
        }
    }
</script>
