document.getElementById("logOut").addEventListener("click", () => {
    if (confirm("Are you sure you want to log out?")) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (xhttp.responseText == "true") {
                    window.location.href = "index.php";
                }
            }
        };
        xhttp.open("GET", "session_unset.php", true);
        xhttp.send();
    }
});