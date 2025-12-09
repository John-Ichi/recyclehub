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
    <link rel="stylesheet" href="Style/user (2).css">

</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Profile</h2>

        <button class="btn-home" id="returnToHome">Home</button>
        <button class="btn-logout" id="logOut">Log Out</button>
        
        <div class="Container">
            <div class="categories">
                <h3>Categories</h3>
                <button class="category-dropdown-btn" id="categoryDropdownBtn">
                    Filter by Category
                </button>
                <div class="category-dropdown-content" id="categoryDropdownContent">
                    <div class="category-item">
                        <input type="checkbox" id="plastic" name="plastic" class="filterPosts" value="Plastic">
                        <label for="plastic">Plastic</label>
                    </div>
                    <div class="category-item">
                        <input type="checkbox" id="paper" name="paper" class="filterPosts" value="Paper">
                        <label for="paper">Paper</label>
                    </div>
                    <div class="category-item">
                        <input type="checkbox" id="glass" name="glass" class="filterPosts" value="Glass">
                        <label for="glass">Glass</label>
                    </div>
                    <div class="category-item">
                        <input type="checkbox" id="wood" name="wood" class="filterPosts" value="Wood">
                        <label for="wood">Wood</label>
                    </div>
                    <div class="category-item">
                        <input type="checkbox" id="scrapMetal" name="scrapMetal" class="filterPosts" value="Scrap Metal">
                        <label for="scrapMetal">Scrap Metal</label>
                    </div>
                    <div class="category-item">
                        <input type="checkbox" id="other" name="other" class="filterPosts" value="Other(s)">
                        <label for="other">Other</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar">
                <span id="profileAvatarLetter">A</span>
            </div>
            <div class="profile-info">
                <h2 id="searchUsername">admin</h2>
                <p id="searchUserEmail">admin@gmail.com</p>
            </div>
            <!-- Follow Button Added Here -->
             <div class="searchUserFollowActions"></div>
        </div>

        <!-- Posts Container -->
        <div class="posts-container posts"></div>
    </div>

    <!-- Hidden Session Info -->
    <div class="currentSessionInfo">
        <h2 id="username"></h2>
        <input type="text" id="userId">
        <p id="userEmail"></p>
    </div>

    <div class="searchUserInfo">
        <input type="text" id="searchUserId">
    </div>

    <!-- Modals -->
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

    <script>
        // Category dropdown toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtn = document.getElementById('categoryDropdownBtn');
            const dropdownContent = document.getElementById('categoryDropdownContent');
            
            if (dropdownBtn && dropdownContent) {
                dropdownBtn.addEventListener('click', function() {
                    dropdownContent.classList.toggle('show');
                    dropdownBtn.classList.toggle('active');
                });
            }
        });
    </script>

    <script src="js/get_user_info.js" defer></script>
    <script src="js/post_deletion_warning_log.js" defer></script>
    <script src="js/ban_warning_log.js" defer></script>
    <script src="js/ban_log.js" defer></script>
    <script src="js/get_search_user_info.js" defer></script>
    <script src="js/display_user_posts.js" defer></script>
    <script src="js/btn_redirect.js" defer></script>
    <script src="js/logout.js" defer></script>
    
<!--
    <script src="js/search.js" defer></script>
-->
</body>
</html>