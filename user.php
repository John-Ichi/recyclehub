<?php

include 'functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}

getAllUsers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecycleHub</title>

    <style>
        img {
            height: 10%;
            width: 10%;
        }
    </style>

</head>
<body>
    Display user info

    <div class="userInfo">
        <h2 id="username"></h2>
        <input type="text" id="userId">
        <p id="userEmail"></p>
    </div>

    <div class="posts"></div>

</body>

<script src="get_search_user_info.js" defer></script>
<script src="display_user_posts.js" defer></script>

</html>