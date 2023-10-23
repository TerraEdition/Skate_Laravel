const show_modal_btn = document.querySelectorAll("#show_stopwatch")
const start_btn = document.querySelector("#start_time")
const finish_btn = document.querySelector("#finish_time")
const show_time = document.getElementById("show_time")
const close_group_btn = document.getElementById("close_group")
const saveTime = document.querySelector('#save_time')
let milliseconds = 0
const modal_stopwatch = new bootstrap.Modal('#timeCompetition')

const input_minute = document.querySelector('#input_minute')
const input_seconds = document.querySelector('#input_seconds')

let ready_screen = false
let mode = 1
show_modal_btn.forEach(btn => {
    btn.addEventListener('click', function () {
        if (!ready_screen) {
            alert("Tampilkan Layar Turnamen Terlebih Dahulu")
        }
    })
})

chooseMode(1);
function chooseMode(model) {
    const modeTitle = document.querySelector('#mode_title')
    const modalStopwatch = document.querySelector('#modal_mode_stopwatch')
    const modalInput = document.querySelector('#modal_mode_input')
    mode = model
    if (mode == 1) {
        modeTitle.textContent = 'Mode Input Manual'
        modalStopwatch.classList.add('d-none')
        modalInput.classList.remove('d-none')
        start_btn.classList.add('d-none')
        saveTime.classList.remove('d-none')
    } else {
        modeTitle.textContent = 'Mode Stopwatch'
        modalInput.classList.add('d-none')
        modalStopwatch.classList.remove('d-none')
        start_btn.classList.remove('d-none')
        saveTime.classList.add('d-none')
    }
}
function format_input_time(time) {
    const [menit, detik] = time.split(':');
    const milidetik = '000'; // Menambahkan milidetik dengan nilai awal 00

    // Menambahkan 0 di depan menit dan detik jika hanya satu digit
    const menitFormatted = menit.length === 1 ? '0' + menit : menit;
    const detikFormatted = detik.length === 1 ? '0' + detik : detik;

    // Menggabungkan menit, detik, dan milidetik
    const waktuFormatted = `${menitFormatted}:${detikFormatted}:${milidetik}`;

    return waktuFormatted;
}
const inputManual = document.querySelector('#mode_input_manual');
inputManual.addEventListener('click', function () {
    chooseMode(1)
})

const inputStopwatch = document.querySelector('#mode_stopwatch');
inputStopwatch.addEventListener('click', function () {
    chooseMode(2)
})


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

async function stop_time(participant_id) {
    modal_stopwatch.hide()
    if (mode == 1) {
        data = await save_time_participant_mode_input(participant_id);
        document.querySelector("#time_participant" + participant_id).textContent = format_input_time(input_minute.value + ":" + input_seconds.value)
    } else {
        clearInterval(startTime);
        milliseconds = 0
        data = await save_time_participant_mode_stopwatch(participant_id);
        document.querySelector("#time_participant" + participant_id).textContent = show_time.textContent
    }
    if (data.status) {
        show_time.textContent = "00:00:000";
    } else {
        alert(data.message)
    }
}

async function save_time_participant_mode_stopwatch(participant_id) {
    try {
        const response = await fetch(base_url + '/api/participant/save-time?participant_id=' + participant_id +
            '&time=' +
            show_time.textContent)
            .then(res => {
                return res.json()
            })
        return response;
    } catch (error) {
        return "Error fetching data:", error;
    }
}
async function save_time_participant_mode_input(participant_id) {
    try {
        const response = await fetch(base_url + '/api/participant/save-time?participant_id=' + participant_id +
            '&time=' +
            format_input_time(input_minute.value + ":" + input_seconds.value))
            .then(res => {
                return res.json()
            })
        return response;
    } catch (error) {
        return "Error fetching data:", error;
    }
}



// Open New Screen
const popupCenter = ({ url, title, w, h }) => {
    // Fixes dual-screen position                             Most browsers      Firefox
    const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
    const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

    const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    const systemZoom = width / window.screen.availWidth;
    const left = (width - w) / 2 / systemZoom + dualScreenLeft
    const top = (height - h) / 2 / systemZoom + dualScreenTop
    return newWindow = window.open(url, title,
        `
        directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,
        scrollbars=yes,
        width=${w / systemZoom},
        height=${h / systemZoom},
        top=${top},
        left=${left}
        `
    )
}

const openScreen = document.querySelector('#new-screen');
openScreen.addEventListener('click', function () {

    const window_screen = popupCenter({ url: current_url + '/screen', title: 'Screen', w: 720, h: 500 });
    window.addEventListener('message', function (event) {
        // Memeriksa sumber pesan
        if (event.source === window_screen) {
            // Melakukan sesuatu dengan pesan yang diterima
            console.log('Pesan dari jendela anak:', event.data);
        }
    });
    // window_screen.onload = function () {
    ready_screen = true
    show_modal_btn.forEach(btn => {
        btn.addEventListener('click', function () {
            if (ready_screen) {
                finish_btn.setAttribute('data-participant_id', this.dataset.participant_id)
                modal_stopwatch.show()
                window_screen.postMessage({ message: "Participant", value: this.dataset.participant_name }, '*');
            }
        })
    })
    start_btn.addEventListener('click', function () {
        start_btn.classList.add('d-none');
        finish_btn.classList.remove('d-none');
        startTime = setInterval(update_time, 10)
        window_screen.postMessage({ message: "Start", value: "00:00:000" }, '*');
    })
    saveTime.addEventListener('click', function () {
        saveTime.classList.add('d-none');
        finish_btn.classList.remove('d-none');
        window_screen.postMessage({ message: "Save", value: format_input_time(input_minute.value + ":" + input_seconds.value) }, '*');
    })

    finish_btn.addEventListener('click', function () {
        finish_btn.classList.add('d-none');
        participant_id = this.dataset.participant_id;
        if (mode == 1) {
            saveTime.classList.remove('d-none');
            window_screen.postMessage({ message: "Stop", value: format_input_time(input_minute.value + ":" + input_seconds.value) }, '*');
        } else {
            start_btn.classList.remove('d-none');
            window_screen.postMessage({ message: "Stop", value: show_time.textContent }, '*');
        }
        stop_time(participant_id);
    })

    // close group if finished
    close_group_btn.addEventListener('click', function () {
        window_screen.postMessage({ message: "Finish", value: '' }, '*');
        window.location.replace(current_url + '/close')
    })
    // }

})
