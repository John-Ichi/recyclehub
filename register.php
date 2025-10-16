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
    <h1>Sign Up</h1>
    <?php
    
    if (isset($_COOKIE['duplicate_email']) || isset($_COOKIE['duplicate_username'])) {
        echo 'Email or username already exists!';
    }

    ?>
    <form action="functions.php" method="POST" autocomplete="off">
        <input type="text" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="sign_up">Sign Up</button>
    </form>
    <a href="index.php">Log In</a>
</body>

<script>
    window.addEventListener("load", () => {
        document.cookie = "duplicate_email=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
        document.cookie = "duplicate_username=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
    });
</script>

</html>