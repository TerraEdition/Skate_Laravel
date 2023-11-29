const table_search = document.getElementById("filter-search");
if (typeof table_search !== "undefined") {
    document.querySelector("#search").addEventListener("keyup", function () {
        // Declare variables
        let filter, tr, i;
        filter = this.value.toLowerCase();
        tr = table_search.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 1; i < tr.length; i++) {
            tr[i].style.display = "none";
            const tdArray = tr[i].getElementsByTagName("td");
            for (var j = 0; j < tdArray.length; j++) {
                const cellValue = tdArray[j];
                if (
                    cellValue &&
                    cellValue.innerHTML.toLowerCase().indexOf(filter) > -1
                ) {
                    tr[i].style.display = "";
                    break;
                }
            }
        }
    });
}
