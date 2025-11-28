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
    <title>RecycleHub - Search Users</title>
    <link rel="stylesheet" href="Style/search.css">

</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🌱 Find Users</h1>
            
            <div class="userInfo" style="display: none;">
                <h2 id="username"></h2>
                <input type="text" id="userId">
                <p id="userEmail"></p>
            </div>

            <div class="header-controls">
                <input type="text" id="searchBar" name="searchUser" placeholder="🔍 Search users..." autocomplete="off">
                <button id="returnToHome" class="btn">Home</button>
                <button id="goToProfile" class="btn">Profile</button>
                <button id="logOut" class="btn">Log Out</button>
            </div>
        </div>

        <div class="users"></div>
    </div>

    <div id="postDeletionNoticeModal" class="modal">
        <div class="modal-content">
            <h2>Notice</h2>
            <div id="noticeDiv"></div>
        </div>
    </div>

    <div id="warningNoticeModal" class="modal">
        <div class="modal-content">
            <h2>Notice</h2>
            <div id="warningNoticeDiv"></div>
        </div>
    </div>

    <script src="js/get_user_info.js" defer></script>
    <script src="js/post_deletion_warning_log.js" defer></script>
    <script src="js/ban_warning_log.js" defer></script>
    <script src="js/ban_log.js" defer></script>
    <script src="js/search.js" defer></script>
    <script src="js/btn_redirect.js" defer></script>
    <script src="js/logout.js" defer></script>
</body>
</html>