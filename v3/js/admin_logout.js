btn = document.getElementById("logOut");
btn.addEventListener("click", () => {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (xhttp.responseText == "true") {
                window.location.href = "admin.php";
            }
        }
    };
    xhttp.open("GET", "session_unset_admin.php", true);
    xhttp.send();
});