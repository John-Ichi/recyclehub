<?php

include 'functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
}

getUserDetails($_SESSION['user']);
getPosts();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style/profile-style.css">
    <title>RecycleHub - Profile</title>
</head>
<body>
    <script>
console.log("PAGE LOADED - User ID:", "<?php echo $_SESSION['user'] ?? 'NOT SET'; ?>");
</script>
    <!-- Left Sidebar -->
    <div class="left-sidebar">
        <h1>Profile</h1>
        
        <input type="text" id="searchBar" name="searchUser" placeholder="Search users..." autocomplete="off">
        <button id="searchButton">Search</button>
        
        <a href="home.php" class="home-button">Home</a>
        
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

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Profile Header Card -->
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

        <!-- Posts Container -->
        <div class="posts-container">
            <div class="posts"></div>
        </div>
    </main>

    <script src="get_user_info.js" defer></script>
    <script src="display_user_posts.js" defer></script>
    <script src="logout.js" defer></script>
    
    <script>
        // Set avatar initial from username
        const usernameElement = document.getElementById('username');
        const observer = new MutationObserver(function() {
            const username = usernameElement.textContent;
            if (username) {
                document.getElementById('avatarInitial').textContent = username.charAt(0).toUpperCase();
            }
        });
        observer.observe(usernameElement, { childList: true, characterData: true, subtree: true });
    </script>

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
</body>
</html>