<?php

include 'functions.php';

// logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: admin.php');
    exit();
}

if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
}

getAllUsers();

getPostsDeletionLog();
getWarningLogs();
getBanLogs();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecycleHub - Admin Dashboard</title>
    <link rel="stylesheet" href="Style/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="header-card">
            <div class="header-left">
                <div class="logo">🌱</div>
                <h1>User Management</h1>
            </div>
            <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" id="searchBar" placeholder="Search users..." autocomplete="off">
            </div>
            <div class="header-actions">
                <button class="btn btn-logout" id="logOut">Log Out</button>
            </div>
        </div>

        <div id="errorMsg" class="error-message"></div>

        <div class="users-grid" id="usersContainer">
            <div class="loading">Loading users...</div>
        </div>
    </div>

    <script>
        const usersContainer = document.getElementById("usersContainer");
        const searchBar = document.getElementById("searchBar");
        const errorMsg = document.getElementById("errorMsg");
        const logOutBtn = document.getElementById("logOut");

        let allUsers = [];
        let deletedLogs = [];
        let warningLogs = [];
        let banLogs = [];

        // Check error messages
        const errorCookie = document.cookie.split('; ').find(row => row.startsWith('post_deletion_error='));
        if (errorCookie) {
            const errorText = decodeURIComponent(errorCookie.split('=')[1]);
            if (errorText) {
                errorMsg.textContent = errorText;
                errorMsg.classList.add('show');
                document.cookie = "post_deletion_error=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
            }
        }

        // Load all data
        Promise.all([
            fetch("users.json").then(res => res.json()),
            fetch("deleted_logs.json").then(res => res.json()).catch(() => []),
            fetch("warning_logs.json").then(res => res.json()).catch(() => []),
            fetch("ban_logs.json").then(res => res.json()).catch(() => [])
        ]).then(([users, deleted, warnings, bans]) => {
            allUsers = users;
            deletedLogs = deleted || [];
            warningLogs = warnings || [];
            banLogs = bans || [];
            renderUsers(allUsers);
        }).catch(error => {
            usersContainer.innerHTML = '<div class="no-users">Error loading users. Please refresh the page.</div>';
            console.error('Error loading data:', error);
        });

        // Search function
        searchBar.addEventListener("input", () => {
            const searchTerm = searchBar.value.trim().toLowerCase();
            if (searchTerm === "") {
                renderUsers(allUsers);
            } else {
                const filtered = allUsers.filter(user => 
                    user.username.trim().toLowerCase().includes(searchTerm)
                );
                renderUsers(filtered);
            }
        });

        function renderUsers(users) {
            if (users.length === 0) {
                usersContainer.innerHTML = '<div class="no-users">No user(s) found.</div>';
                return;
            }

            usersContainer.innerHTML = "";

            users.forEach(user => {
                const stats = getUserStats(user.userId);
                
                const card = document.createElement("div");
                card.className = "user-card";
                card.innerHTML = `
                    <div class="user-header">
                        <a href="user_actions.php?user=${user.userId}" class="username">${user.username}</a>
                        <span class="status-badge ${stats.banStatus === 'Banned' ? 'status-banned' : 'status-unbanned'}">
                            ${stats.banStatus}
                        </span>
                    </div>
                    <div class="user-stats">
                        <div class="stat-item">
                            <span class="stat-label">Warnings</span>
                            <span class="stat-value ${stats.numOfWarnings > 0 ? 'warning' : ''}">${stats.numOfWarnings}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Confirmed Warnings</span>
                            <span class="stat-value ${stats.numOfConfirmedWarnings > 0 ? 'danger' : ''}">${stats.numOfConfirmedWarnings}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Deleted Posts</span>
                            <span class="stat-value ${stats.numOfDeletedPosts > 0 ? 'warning' : ''}">${stats.numOfDeletedPosts}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Bans Received</span>
                            <span class="stat-value ${stats.banHistory > 0 ? 'danger' : ''}">${stats.banHistory}</span>
                        </div>
                    </div>
                `;
                usersContainer.appendChild(card);
            });
        }

        function getUserStats(userId) {
            let numOfWarnings = 0;
            let numOfConfirmedWarnings = 0;
            let numOfDeletedPosts = 0;
            let banStatus = "Unbanned";
            let banHistory = 0;

            // Count warnings
            if (warningLogs && warningLogs.length > 0) {
                const userWarnings = warningLogs.filter(log => log.userId === userId);
                numOfWarnings = userWarnings.length;
                numOfConfirmedWarnings = userWarnings.filter(log => log.confirmed === "1").length;
            }

            // Count deleted posts
            if (deletedLogs && deletedLogs.length > 0) {
                numOfDeletedPosts = deletedLogs.filter(log => log.userId === userId).length;
            }

            // Check ban status
            if (banLogs && banLogs.length > 0) {
                const userBans = banLogs.filter(log => log.userId === userId);
                banHistory = userBans.length;
                const activeBan = userBans.find(log => log.unban === "0");
                if (activeBan) {
                    banStatus = "Banned";
                }
            }

            return { numOfWarnings, numOfConfirmedWarnings, numOfDeletedPosts, banStatus, banHistory };
        }

        // Logout function
        logOutBtn.addEventListener("click", () => {
            window.location.href = "dashboard.php?logout=1";
        });
    </script>
</body>
</html>