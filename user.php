<?php

include 'functions.php';

if (!isset($_SESSION['user']) && !isset($_SESSION['username']) && !isset($_SESSION['useremail'])) {
    header('Location: index.php');
}

getAllUsers();
getPosts();
getComments();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecycleHub</title>
    <link rel="stylesheet" href="modal.css">
</head>
<body>
    <button id="returnToHome">Home</button>
    <button id="goToSearch">Search</button>
    <button id="goToProfile">Profile</button>
    <button id="logOut">Log out</button>

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
    
    <div class="filterSelect">
        <input type="checkbox" id="plastic" name="plastic" class="filterPosts" value="Plastic">
        <label for="plastic">Plastic</label>
        <input type="checkbox" id="paper" name="paper" class="filterPosts" value="Paper">
        <label for="paper">Paper</label>
        <input type="checkbox" id="glass" name="glass" class="filterPosts" value="Glass">
        <label for="glass">Glass</label>
        <input type="checkbox" id="wood" name="wood" class="filterPosts" value="Wood">
        <label for="wood">Wood</label>
        <input type="checkbox" id="scrapMetal" name="scrapMetal" class="filterPosts" value="Scrap Metal">
        <label for="scrapMetal">Scrap Metal</label>
        <input type="checkbox" id="other" name="other" class="filterPosts" value="Other(s)">
        <label for="other">Other</label>
    </div>

    <div class="posts"></div>

    <div id="commentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Comments</h2>
            <div id="comments"></div>
            <div id="postComments"></div>
        </div>
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
</body>

<script src="js/get_user_info.js" defer></script>

<script src="js/post_deletion_warning_log.js" defer></script>

<script src="js/ban_warning_log.js" defer></script>

<script src="js/ban_log.js" defer></script>

<script src="js/get_search_user_info.js" defer></script>

<script src="js/display_user_posts.js" defer></script>

<script src="js/btn_redirect.js" defer></script>

<script src="js/logout.js" defer></script>

</html>