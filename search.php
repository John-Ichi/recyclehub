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

getAllUsers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecycleHub</title>

    <style>
        .followeeInput, .followerInput, .submitInput {
            display: none;
        }
    </style>

</head>
<body>
    <h1>Users</h1>

    <div class="userInfo" style="display: none;">
        <h2 id="username"></h2>
        <input type="text" id="userId">
        <p id="userEmail"></p>
    </div>
    
    <input type="text" id="searchBar" name="searchUser" placeholder="Search users..." autocomplete="off">
    
    <button id="returnToHome">Home</button>
    
    <button id="goToProfile">Profile</button>
    
    <button id="logOut">Log Out</button>
    <div class="users"></div>
</body>

<script src="js/get_user_info.js" defer></script>

<script src="js/search.js" defer></script>

<script src="js/logout.js" defer></script>

<script>
    document.getElementById("returnToHome").addEventListener("click", () => {
        window.location.href = "home.php";
    })

    document.getElementById("goToProfile").addEventListener("click", () => {
        window.location.href = "profile.php";
    })
</script>

</html>