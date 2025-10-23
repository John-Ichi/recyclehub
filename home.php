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

        .thumbnail {
            height: 10%;
            width: 10%;
        }

        img {
            height: 10%;
            width: 10%;
        }
    </style>

</head>
<body>
    <h1>News Feed</h1>
    
    <input type="text" id="searchBar" name="searchUser" placeholder="Search users..." autocomplete="off">
    <button id="searchButton">Search</button>
    
    <a href="profile.php">Profile</a>
    
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

    <div class="posts"></div> <!-- Posts -->

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
                
                <button type="submit" name="create_post">Create Post</button>
            
            </form>

        </div>

    </div>
</body>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<script> // Set user ID for posting
    fetch("user_info.json")
    .then(res => res.json())
    .then(data => {
        document.getElementById("imageUploadUserId").value = data[0].userId;
    });
</script>

<script src="home.js" defer></script>

<script src="verify_post.js" defer></script>

<script src="logout.js" defer></script>

<script>
    // Search script
    const searchBar = document.getElementById("searchBar");
    searchBar.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault(); //

            const searchInput = searchBar.value.trim();

            if (searchInput !== "") {
                const urlParameters = "searchUser=" + searchInput;
                window.open(`search.php?${urlParameters}`);
            }
        }
    });

    const searchButton = document.getElementById("searchButton");
    searchButton.addEventListener("click", () => {
        const searchInput = searchBar.value.trim();

        if (searchInput !== "") {
            const urlParameters = "searchUser=" + searchInput;
            window.open("search.php?" + urlParameters);
        } else {
            window.open("search.php");
        }
    });
</script>

</html>