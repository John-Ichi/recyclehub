<?php

include 'functions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecycleHub</title>
</head>
<body>
    <form action="functions.php" method="POST" autocomplete="off">
        <input type="text" name="admin_username" placeholder="Username" required>
        <input type="password" name="admin_password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm password" required>
        <input type="hidden" name="admin_register" value="true">
        <button type="submit" name="register_admin">Register</button>
    </form>
</body>
</html>