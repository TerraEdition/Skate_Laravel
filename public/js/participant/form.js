const team = document.querySelector("#team")

async function getTeam() {
    try {
        const response = await fetch(base_url + '/api/team/search?team=' + team.value);
        const data = await response.json();
        return data;
    } catch (error) {
        return "Error fetching data:", error;
    }
}


team.addEventListener("keyup", function () {
    clearTimeout(timeout);
    timeout = setTimeout(function () {
        console.log(getTeam());
    }, delay);
});

// autocomplete(document.getElementById("team"), countries);
