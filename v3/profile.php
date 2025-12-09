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
    <link rel="stylesheet" href="Style/modal (1).css">
    <title>RecycleHub</title>
</head>
<body>
    <script>
        console.log("PAGE LOADED - User ID:", "<?php echo $_SESSION['user'] ?? 'NOT SET'; ?>");
    </script>

    <div class="left-sidebar">
        <h1>Profile</h1>
        
        <input type="text" id="searchBar" name="searchUser" placeholder="Search users..." autocomplete="off">
        <button id="searchButton">Search</button>
        
        <a href="home.php" class="home-button">Home</a>
        
        <button id="logOut">Log Out</button>

        <div class="filterSelect">
            <div class="category-header" id="categoryHeader">
                <h3>Categories</h3>
                <span class="dropdown-arrow">▼</span>
            </div>
            <div class="category-items" id="categoryItems">
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

    <main class="main-content">
        <div class="profile-header">
            <div class="user-avatar">
                <span id="avatarInitial"></span>
            </div>
            <div class="user-details">
                <h2 id="username"></h2>
                <input type="hidden" id="userId">
                <p id="userEmail"></p>
            </div>
        </div>

        <div class="posts-container">
            <div class="posts"></div>
        </div>
    </main>

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
                <input type="hidden" name="location" value="profile_page">
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

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            const username = "<?php echo $_SESSION['username'] ?? ''; ?>";
            const email = "<?php echo $_SESSION['useremail'] ?? ''; ?>";
            const userId = "<?php echo $_SESSION['user'] ?? ''; ?>";
            
            document.getElementById('username').textContent = username;
            document.getElementById('userEmail').textContent = email;
            document.getElementById('userId').value = userId;

            if (username) {
                document.getElementById('avatarInitial').textContent = username.charAt(0).toUpperCase();
            }
            const categoryHeader = document.getElementById('categoryHeader');
            const categoryItems = document.getElementById('categoryItems');
            const dropdownArrow = categoryHeader.querySelector('.dropdown-arrow');

            categoryHeader.addEventListener('click', function() {
                categoryItems.classList.toggle('show');
                dropdownArrow.classList.toggle('open');
            });
        });
    </script>

    <script src="js/get_user_info.js" defer></script>
    <script src="js/post_deletion_warning_log.js" defer></script>
    <script src="js/ban_warning_log.js" defer></script>
    <script src="js/ban_log.js" defer></script>
    <script src="js/display_user_posts.js" defer></script>
    <script src="js/verify_post.js" defer></script>
    <script src="js/btn_redirect.js" defer></script>
    <script src="js/logout.js" defer></script>
    
</body>
</html>