<?php

include 'functions.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
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
    <link rel="stylesheet" href="Style/user-actiom (1).css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 12px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .post img {
            max-height: 400px;
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Top Button Container -->
    <div class="button-container">
        <button id="returnToDashboard">Return</button>
        <button id="warningBtn">Warn User</button>
        <button id="banBtn">Ban User</button>
    </div>

    <!-- Confirmation Message -->
    <p id="confirmationMessage">
        <?php
        
        if (isset($_COOKIE['delete_successful'])) {
            echo $_COOKIE['delete_successful'];
        }

        if (isset($_COOKIE['warning_successful'])) {
            echo $_COOKIE['warning_successful'];
        }

        if (isset($_COOKIE['ban_successful'])) {
            echo $_COOKIE['ban_successful'];
        }

        if (isset($_COOKIE['unban_succesful'])) {
            echo $_COOKIE['unban_succesful'];
        }

        ?>
    </p>

    <!-- Main Content with Two Columns -->
    <div class="main-content">
        <!-- Left Column -->
        <div class="left-column">
            <!-- User Info Card -->
            <div class="userInfo">
                <h2 id="username"></h2>
                <input type="hidden" id="userId" readonly>
                <p id="userEmail"></p>
                <!-- Dynamic stats will be appended here by JavaScript -->
            </div>

            <!-- Category Filters -->
            <div class="filterSelect">
                <label>
                    <input type="checkbox" id="plastic" name="plastic" class="filterPosts" value="Plastic">
                    Plastic
                </label>
                <label>
                    <input type="checkbox" id="paper" name="paper" class="filterPosts" value="Paper">
                    Paper
                </label>
                <label>
                    <input type="checkbox" id="glass" name="glass" class="filterPosts" value="Glass">
                    Glass
                </label>
                <label>
                    <input type="checkbox" id="wood" name="wood" class="filterPosts" value="Wood">
                    Wood
                </label>
                <label>
                    <input type="checkbox" id="scrapMetal" name="scrapMetal" class="filterPosts" value="Scrap Metal">
                    Scrap Metal
                </label>
                <label>
                    <input type="checkbox" id="other" name="other" class="filterPosts" value="Other(s)">
                    Other
                </label>
            </div>
        </div>

        <!-- Right Column - Posts -->
        <div class="right-column">
            <div class="posts"></div>
        </div>
    </div>

    <!-- Warning Modal -->
    <div id="warningModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>User Warning</h2>
            <div id="warningDiv">
                <p>Send a warning message to the user:</p>
                <form action="functions.php" method="POST">
                    <input type="hidden" name="user_id" class="userId" readonly>
                    <textarea name="warning_message" placeholder="Enter warning message..." required></textarea>
                    <input type="hidden" name="warn_user" value="true">
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Post Modal -->
    <div id="deletePostModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Delete post?</h2>
            <p>Are you sure you want to delete this post?</p>
            <form action="functions.php" method="POST">
                <input type="hidden" name="user_id" class="userId">
                <input type="hidden" name="post_id" class="postId">
                <select name="deletion_purpose" required>
                    <option value="" selected disabled hidden>Reason for deletion</option>
                    <option value="Inappropriate_image(s)">Inappropriate pictures</option>
                    <option value="Inappropriate_caption">Inappropriate captions</option>
                    <option value="Inappropriate_comment(s)">Inappropriate comments</option>
                </select>
                <button type="submit" name="delete_post">Yes</button>
            </form>
        </div>
    </div>

    <!-- Ban User Modal -->
    <div id="banUserModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ban User</h2>
            <div id="banUserDiv">
                <p>Write a ban message for the user</p>
                <form action="functions.php" method="POST" autocomplete="off">
                    <input type="hidden" name="user_id" class="userId">
                    <textarea name="ban_message" placeholder="Enter ban reason..." required></textarea>
                    <input type="hidden" name="ban_user" class="banInput" value="true">
                    <button type="submit">Confirm</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Comments Modal -->
    <div id="commentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Comments</h2>
            <div id="comments"></div>
        </div>
    </div>

    <!-- Cookie Cleanup Script -->
    <script>
        window.addEventListener("load", () => {
            document.cookie = "delete_successful=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
            document.cookie = "warning_successful=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
            document.cookie = "ban_successful=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
            document.cookie = "unban_succesful=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
        });
    </script>

    <!-- Return to Dashboard Script -->
    <script>
        document.getElementById("returnToDashboard").addEventListener("click", () => {
            window.location.href = "dashboard.php";
        });
    </script>

    <!-- Main JavaScript -->
    <script src="js/user_actions.js" defer></script>

</body>
</html>