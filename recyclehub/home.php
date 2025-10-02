<?php

include 'functions.php';

if (!isset($_SESSION['email']) && !isset($_SESSION['username']) && !isset($_SESSION['user'])) {
    header('Location: index.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecycleHub</title>
</head>
<body>
    <h1>News Feed</h1>
    <button id="logOut">Log Out</button>
</body>

<script>
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
</script>

</html>