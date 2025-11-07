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

getBanLogs();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecycleHub</title>
</head>
<body>
    
    <button id="logOut">Log out</button>

    <div id="userinfo">
        <h2 id="username"></h2>
        <input type="hidden" id="userId">
        <p id="userEmail"></p>
    </div>

    <div id="banNoticeDiv"></div>

</body>

<script src="js/get_user_info.js" defer></script>

<script src="js/ban_log.js" defer></script>

<script src="js/logout.js" defer></script>

</html>