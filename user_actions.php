<?php

include 'functions.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
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
            background-color: rgb(0,0,0,);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
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

        img {
            height: 10%;
            width: 10%;
        }
    </style>
</head>
<body>
    <p id="confirmationMessage">
        <?php
        
        if (isset($_COOKIE['delete_successful'])) {
            echo $_COOKIE['delete_successful'];
        }

        ?>
    </p>

    <button id="returnToDashboard">Return</button>
    
    <button id="warningBtn">Warn User</button>

    <button id="banBtn">Ban User</button>

    <div class="userInfo">
        <h2 id="username"></h2>
        <input type="text" id="userId">
        <p id="userEmail"></p>
    </div>

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
        </div>
    </div>

    <div id="deletePostModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Delete post?</h2>
            <p>Are you sure you want to delete this post?</p>
            <form action="actions.php" method="POST">
                <input type="text" name="user_id" id="userId">
                <input type="text" name="post_id" id="postId">
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

</body>

<script>
    window.addEventListener("load", () => {
        document.cookie = "delete_successful=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
    });
</script>

<script>
    document.getElementById("returnToDashboard").addEventListener("click", () => {
        window.location.href = "dashboard.php";
    });
</script>

<script src="js/user_actions.js" defer></script>

</html>