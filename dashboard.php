<?php

include 'functions.php';

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
    <title>RecycleHub</title>
</head>
<body>
    <p id="errorMsg">
        <?php
        
        if (isset($_COOKIE['post_deletion_error'])) {
            echo $_COOKIE['post_deletion_error'];
        }

        ?>
    </p>

    <button id="logOut">Logout</button>

<!-- Search users -->

    <input type="text" id="searchBar" name="searchUser" placeholder="Search users..." autocomplete="off">

<!-- Display all users -->

    <div class="users"></div>

</body>

<script>
    window.addEventListener("load", () => {
        document.cookie = "post_deletion_error=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
    });
</script>

<script src="js/dashboard.js" defer></script>

<script src="js/admin_logout.js" defer></script>

</html>