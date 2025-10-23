<?php

include 'functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}

getUserDetails($_SESSION['user']);
getPosts();

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
    <h1>User Info</h1>
    <a href="home.php">Home</a>
    <h2 id="username"></h2>
    <input type="hidden" id="userId">
    <p id="userEmail"></p>
    <h3>Posts</h3>
    <div class="posts"></div>
</body>

<script src="get_user_info.js" defer></script>

<script src="display_user_posts.js" defer></script>

</html>