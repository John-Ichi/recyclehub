<?php

include 'functions.php';

if (isset($_SESSION['admin'])) {
    header('Location: dashboard.php');
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
    <p id="errorMsg">
        <?php

        if (isset($_COOKIE['admin_error'])) {
            echo $_COOKIE['admin_error'];
        }

        ?>
    </p>

    <button id="backBtn">Back</button>
    <form action="functions.php" method="POST" autocomplete="off">
        <input type="text" name="admin" placeholder="Admin">
        <input type="password" name="password" placeholder="Password">
        <button type="submit" name="admin_login">Login</button>
    </form>
</body>

<script>
    const backBtn = document.getElementById("backBtn");
    backBtn.addEventListener("click", () => {
        window.location.href = "index.php";
    });
</script>

<script>
    window.addEventListener("load", () => {
        document.cookie = "admin_error=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
    });
</script>

</html>