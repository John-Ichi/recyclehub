btn = document.getElementById("logOut"); // Log out API call
btn.addEventListener("click", () => {
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
});