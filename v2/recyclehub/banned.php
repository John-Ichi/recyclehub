<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecycleHub - Account Suspended</title>
    <link rel="stylesheet" href="Style/banned-style.css">
</head>
<body>
    <h2 class="brand">BANNED</h2>

    <div class="wrapper">
        <div class="content-container">
            <div class="ban-header">
                <div class="ban-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm6.31-3.1L7.1 5.69C8.45 4.63 10.15 4 12 4c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z"/>
                    </svg>
                </div>
                <h1>Account Suspended</h1>
                <p class="ban-subtitle">Your access has been restricted</p>
            </div>

            <div class="user-info">
                <h3 id="username"></h3>
                <input type="hidden" id="userId">
                <p id="userEmail"></p>
            </div>

            <div class="ban-details" id="banNoticeDiv">
                <!-- Ban information will be populated by ban_log.js -->
            </div>

            <div class="info-note">
                <p><strong>What does this mean?</strong> You can no longer access RecycleHub services. If you believe this is a mistake, please contact our support team.</p>
            </div>

            <div class="button-group">
                <button class="btn-secondary" id="logOut">Log Out</button>
                <a href="mailto:support@recyclehub.com" class="button btn-primary">Contact Support</a>
            </div>
        </div>
    </div>

    <script src="js/get_user_info.js" defer></script>
    <script src="js/ban_log.js" defer></script>
    <script src="js/logout.js" defer></script>
</body>
</html>