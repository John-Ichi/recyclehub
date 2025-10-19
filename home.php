<?php

include 'functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}

getAllUsers();
getUserDetails($_SESSION['user']);
getPosts();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style/home-style.css">
    <title>RecycleHub</title>
</head>
<script>
// Quick test - try loading one image directly
const testImg = new Image();
testImg.onload = () => console.log('✓ Test image loaded successfully');
testImg.onerror = () => console.error('✗ Test image failed - check path and folder permissions');
testImg.src = 'uploads/img_68ece410a68312.56239217.jpg'; // Use one of your actual image names
console.log('Testing image path:', testImg.src);
</script>
<body>
    <div class="left-sidebar">
    <h1>News Feed</h1>
    
    <input type="text" id="searchBar" name="searchUser" placeholder="Search users..." autocomplete="off">
    <button id="searchButton">Search</button>
    
    <a href="profile.php">Profile</a>
    
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
</div>

<div class="content-area">
    <!-- Create Post Card - Sticky at top -->
    <div class="create-post-wrapper">
        <div class="create-post-card" id="createPostCard">
            <div class="create-post-header">
                <div class="create-post-avatar"></div>
                <div class="create-post-input">What's on your mind?</div>
            </div>
        </div>
    </div>

    <div class="posts"></div> <!-- Posts -->
            
            <!-- Posts will be dynamically loaded here by JavaScript -->
</div>

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

                <textarea name="text_content" rows="10" cols="100%" maxlength="250" placeholder="Share your recycling ideas..."></textarea>
                
                <button type="submit" name="create_post">Create Post</button>
            
            </form>

        </div>
    </div>
    <script src="hybrid_image_display.js" defer></script>
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
            e.preventDefault();

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

<script>
    // Open modal when clicking the create post card
    document.getElementById('createPostCard').addEventListener('click', function() {
        document.getElementById('postModal').style.display = 'block';
    });

    // Close modal when clicking the X
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('postModal').style.display = 'none';
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('postModal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>
<script>
// Image Preview Functionality
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('previewContainer');
    previewContainer.innerHTML = ''; // Clear existing previews
    
    const files = e.target.files;
    
    if (files.length > 0) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
               reader.onload = function(e) {
    const img = document.createElement('img');
    img.src = e.target.result;
    img.style.maxWidth = '400px';  // Maximum width
    img.style.maxHeight = '400px'; // Maximum height
    img.style.width = 'auto';
    img.style.height = 'auto';
    img.style.objectFit = 'contain';
    img.style.borderRadius = '8px';
    img.style.border = '2px solid #c8dfc8';
    img.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
    previewContainer.appendChild(img);
};            
                reader.readAsDataURL(file);
            }
        });
    }
});

// Clear preview when modal closes
document.querySelector('.close').addEventListener('click', function() {
    document.getElementById('previewContainer').innerHTML = '';
    document.getElementById('postForm').reset();
});

window.addEventListener('click', function(e) {
    const modal = document.getElementById('postModal');
    if (e.target === modal) {
        document.getElementById('previewContainer').innerHTML = '';
        document.getElementById('postForm').reset();
    }
});
</script>

</html>