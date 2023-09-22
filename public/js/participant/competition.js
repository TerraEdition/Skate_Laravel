const show_modal_btn = document.querySelectorAll("#show_stopwatch")
const start_btn = document.querySelector("#start_time")
const finish_btn = document.querySelector("#finish_time")
const show_time = document.getElementById("show_time")
const close_group_btn = document.getElementById("close_group")
let milliseconds = 0
const modal_stopwatch = new bootstrap.Modal('#timeCompetition')
let ready_screen = false
show_modal_btn.forEach(btn => {
    btn.addEventListener('click', function () {
        if (!ready_screen) {
            alert("Tampilkan Layar Turnamen Terlebih Dahulu")
        }
    })
})


function toggle_btn() {
    finish_btn.classList.toggle('d-none');
    start_btn.classList.toggle('d-none');
}

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
    clearInterval(startTime);
    modal_stopwatch.hide()
    milliseconds = 0
    data = await save_time_participant(participant_id);
    if (data.status) {
        document.querySelector("#time_participant" + participant_id).textContent = show_time.textContent
        show_time.textContent = "00:00:000";
    } else {
        alert(data.message)
    }
}

async function save_time_participant(participant_id) {
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
        toggle_btn()
        startTime = setInterval(update_time, 10)
        window_screen.postMessage({ message: "Start", value: "00:00:000" }, '*');
    })

    finish_btn.addEventListener('click', function () {
        toggle_btn()
        participant_id = this.dataset.participant_id;
        stop_time(participant_id);
        window_screen.postMessage({ message: "Stop", value: show_time.textContent }, '*');
    })

    // close group if finished
    close_group_btn.addEventListener('click', function () {
        window_screen.postMessage({ message: "Finish", value: '' }, '*');
        window.location.replace(current_url + '/close')
    })
    // }

})
