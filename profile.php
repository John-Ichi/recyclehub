<?php

include 'functions.php';

if (!isset($_SESSION['user']) && !isset($_SESSION['username']) && !isset($_SESSION['useremail'])) {
    header('Location: index.php');
}

if (isset($_SESSION['user'])) {
    getUserDetails($_SESSION['user']);
} else if (isset($_SESSION['username'])) {
    getUserDetails($_SESSION['username']);
} else if (isset($_SESSION['useremail'])) {
    getUserDetails($_SESSION['useremail']);
}

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
    <input type="text" id="userId" style="display: none;">
    <p id="userEmail" style="display: none;"></p>
    <h3>Posts</h3>
    <div class="posts"></div>
</body>

<script src="js/get_user_info.js" defer></script>

<script src="js/display_user_posts.js" defer></script>

</html>