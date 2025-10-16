<?php

include 'functions.php';

if (isset($_SESSION['user'])) {
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
    <h1>Log In</h1>
    <?php
    
    if (isset($_COOKIE['login_error'])) {
        echo $_COOKIE['login_error'];
    }
    
    ?>
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
</script>

</html>