const show_modal_btn = document.querySelectorAll("#show_stopwatch")
const start_btn = document.querySelector("#start_time")
const finish_btn = document.querySelector("#finish_time")
const show_time = document.getElementById("show_time")
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
    // modal_stopwatch.hide()
    // data = await save_time_participant(participant_id);
    // if (data.status) {
    //     document.querySelector("#time_participant" + participant_id).textContent = show_time.textContent
    //     // document.querySelector(`#show_stopwatch[data-participant_id="${participant_id}"]`).remove()
    //     show_time.textContent = "00:00:000";
    // } else {
    //     alert(data.message)
    // }
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
const openScreen = document.querySelector('#new-screen');
openScreen.addEventListener('click', function () {
    const params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,width=600,height=300,left=100,top=100`;
    const window_screen = open(current_url + '/screen', 'Screen', params);
    window.addEventListener('message', function (event) {
        // Memeriksa sumber pesan
        if (event.source === window_screen) {
            // Melakukan sesuatu dengan pesan yang diterima
            console.log('Pesan dari jendela anak:', event.data);
        }
    });
    window_screen.onload = function () {
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
            milliseconds = 0
            startTime = setInterval(update_time, 10)
            window_screen.postMessage({ message: "Start", value: "00:00:000" }, '*');
        })

        finish_btn.addEventListener('click', function () {
            toggle_btn()
            participant_id = this.dataset.participant_id;
            stop_time(participant_id);
            window_screen.postMessage({ message: "Stop", value: show_time.textContent }, '*');
        })
    }

})
