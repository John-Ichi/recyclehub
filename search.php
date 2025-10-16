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
</head>
<body>
    <h1>Users</h1>

    <div class="userInfo" style="display: none;">
        <h2 id="username"></h2>
        <input type="text" id="userId">
        <p id="userEmail"></p>
    </div>

    <input type="text" id="searchBar" name="searchUser" placeholder="Search users..." autocomplete="off">

    <a href="home.php">Home</a>

    <button id="logOut">Log Out</button>

    <div class="users"></div>
</body>

<script src="get_user_info.js" defer></script>

<script src="search.js" defer></script>

<script src="logout.js" defer></script>

</html>