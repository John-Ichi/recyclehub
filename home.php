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
getPosts();
getComments();

getPostsDeletionLog();
getWarningLogs();
getBanLogs();

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
    <div class="userInfo" style="display: none;">
        <p id="username"></p>
        <input type="text" id="userId">
        <p id="userEmail"></p>
    </div>

    <h1>News Feed</h1>
    
    <input type="text" id="searchBar" name="searchUser" placeholder="Search users..." autocomplete="off">
    <button id="searchButton">Search</button>
    
    <button id="goToProfile">Profile</button>
    
    <button id="createPost">Create Post</button>
    <button id="logOut">Log Out</button>

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

    <div id="postModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>What are your recycling ideas?</h2>
            <form id="postForm" action="functions.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" id="imageUploadUserId">
                <input type="file" name="images[]" id="imageUpload" accept="image/png, image/jpeg" multiple required>
                <select name="category" id="postCategory" required>
                    <option value="" selected disabled hidden></option>
                    <option value="Plastic">Plastic</option>
                    <option value="Paper">Paper</option>
                    <option value="Glass">Glass</option>
                    <option value="Wood">Wood</option>
                    <option value="Scrap Metal">Scrap Metal</option>
                    <option value="Other(s)">Other(s)</option>
                </select>
                    <div id="previewContainer"></div><br>
                <textarea name="text_content" rows="10" cols="100%" maxlength="250"></textarea>
                <input type="hidden" name="location" value="home_page">
                <button type="submit" name="create_post">Create Post</button>
            </form>
        </div>
    </div>

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

<script src="js/home.js" defer></script>

<script src="js/verify_post.js" defer></script>

<script src="js/btn_redirect.js" defer></script>

<script src="js/logout.js" defer></script>

</html>