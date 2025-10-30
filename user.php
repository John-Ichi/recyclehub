<?php

include 'functions.php';

if (!isset($_SESSION['user']) && !isset($_SESSION['username']) && !isset($_SESSION['useremail'])) {
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
    <div class="currentSessionInfo" style="display: none;">
        <h2 id="username"></h2>
        <input type="text" id="userId">
        <p id="userEmail"></p>
    </div>
    <div class="searchUserInfo">
        <h2 id="searchUsername"></h2>
        <input type="text" id="searchUserId" style="display: none;">
        <p id="searchUserEmail" style="display: none;"></p>
    </div>
    <div class="searchUserFollowActions"></div>
    <div class="posts"></div>
</body>

<script src="js/get_user_info.js"></script>

<script src="js/get_search_user_info.js" defer></script>

<script src="js/display_user_posts.js" defer></script>

</html>