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
    <link rel="stylesheet" href="Style/home-style.css">
</head>
<body>
    <!-- Left Sidebar -->
    <div class="left-sidebar">
        <h1>News Feed</h1>

        <input type="text" id="searchBar" name="searchUser" placeholder="Search users..." autocomplete="off">
        <button id="searchButton">Search</button>
        
        <a href="profile.php">Profile</a>
        
        <button id="logOut">Log Out</button>

        <div class="filterSelect">
            <button class="category-dropdown-btn" id="categoryDropdownBtn">Categories</button>
            <div class="category-dropdown-content" id="categoryDropdownContent">
                <label for="plastic">
                    <input type="checkbox" id="plastic" name="plastic" class="filterPosts" value="Plastic">
                    Plastic
                </label>
                <label for="paper">
                    <input type="checkbox" id="paper" name="paper" class="filterPosts" value="Paper">
                    Paper
                </label>
                <label for="glass">
                    <input type="checkbox" id="glass" name="glass" class="filterPosts" value="Glass">
                    Glass
                </label>
                <label for="wood">
                    <input type="checkbox" id="wood" name="wood" class="filterPosts" value="Wood">
                    Wood
                </label>
                <label for="scrapMetal">
                    <input type="checkbox" id="scrapMetal" name="scrapMetal" class="filterPosts" value="Scrap Metal">
                    Scrap Metal
                </label>
                <label for="other">
                    <input type="checkbox" id="other" name="other" class="filterPosts" value="Other(s)">
                    Other
                </label>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="content-area">
        <!-- Create Post Card (Sticky) -->
        <div class="create-post-wrapper">
            <div class="create-post-card" id="createPostCard">
                <div class="create-post-header">
                    <div class="create-post-avatar"></div>
                    <div class="create-post-input">What's on your mind?</div>
                </div>
            </div>
        </div>

        <!-- Posts -->
        <div class="posts"></div>
    </div>

    <!-- Post Modal -->
    <div id="postModal" class="modal">
        <div class="modal-content">
            <h2>What are your recycling ideas?</h2>
            <form id="postForm" action="functions.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" id="imageUploadUserId">
                <input type="file" name="images[]" id="imageUpload" accept="image/png, image/jpeg" multiple required>
                <select name="category" id="postCategory" required>
                    <option value="" selected disabled hidden>Select Category</option>
                    <option value="Plastic">Plastic</option>
                    <option value="Paper">Paper</option>
                    <option value="Glass">Glass</option>
                    <option value="Wood">Wood</option>
                    <option value="Scrap Metal">Scrap Metal</option>
                    <option value="Other(s)">Other(s)</option>
                </select>
                <div id="previewContainer"></div>
                <textarea name="text_content" rows="10" cols="100%" maxlength="250" placeholder="Share your recycling ideas..."></textarea>
                <input type="hidden" name="location" value="home_page">
                <div class="modal-buttons">
                    <button type="submit" name="create_post" class="btn-create">Create Post</button>
                    <button type="button" id="cancelPostBtn" class="btn-cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Comment Modal -->
    <div id="commentModal" class="modal">
        <div class="modal-content">
            <span class="close-comment" id="closeCommentBtn">&times;</span>
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
<!--Drop down-->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownBtn = document.getElementById('categoryDropdownBtn');
    const dropdownContent = document.getElementById('categoryDropdownContent');
    
    if (dropdownBtn && dropdownContent) {
        dropdownBtn.addEventListener('click', function() {
            dropdownContent.classList.toggle('show');
            dropdownBtn.classList.toggle('active');
        });
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.filterSelect')) {
                dropdownContent.classList.remove('show');
                dropdownBtn.classList.remove('active');
            }
        });
    }
});
</script>
</html>