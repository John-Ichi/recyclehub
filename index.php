<?php

include 'functions.php';

if (isset($_SESSION['user']) || isset($_SESSION['username']) || isset($_SESSION['useremail'])) {
    header('Location: home.php');
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

    <button id="adminBtn">Admin</button>

    <h1>Log In</h1>
    <p id="errorMsg">
        <?php

        if (isset($_COOKIE['login_error'])) {
            echo $_COOKIE['login_error'];
        }

        ?>
    </p>
    <form action="functions.php" method="POST" autocomplete="off">
        <input type="text" name="user" placeholder="Email or username">
        <input type="password" name="password" placeholder="Password">
        <button type="submit" name="log_in">Log In</button>
    </form>
    <a href="register.php">Register</a>
</body>

<script>
    window.addEventListener("load", () => {
        document.cookie = "login_error=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
    });

    const adminBtn = document.getElementById("adminBtn");
    adminBtn.addEventListener("click", () => {
        window.location.href = "admin.php";
    });
</script>

</html>