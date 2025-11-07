fetch("ban_logs.json")
.then(res => res.json())
.then(data => {
    if (data === null) {
        return;
    }

    var userId;

    if (document.getElementById("imageUploadUserId") !== null) {
        userId = document.getElementById("imageUploadUserId").value;
    } else {
        userId = document.getElementById("userId").value;
    }

    const userBanLogs = data.filter(log => log.userId === userId);

    if (userBanLogs.length === 0) return;

    let banStatus = "Unbanned";

    userBanLogs.forEach(log => {
        if (log.unban === "0") {
            banStatus = "Banned";
        }
    });

    if (banStatus === "Banned") {
        if (!window.location.pathname.endsWith("banned.php")) {
            window.location.href = "banned.php";
        }

        let banSummary = "";

        userBanLogs.forEach(log => {
            if (log.unban === "0") {
                banSummary += `Ban reason: ${log.reason}\n`;
            }
        });

        const notice = document.createElement("div");
        notice.classList.add("notice");

        const message = document.createElement("p");
        message.classList.add("noticeMessage");
        message.innerHTML = `Your account is banned!<br>Summary:<br>${banSummary}`;

        notice.appendChild(message);

        const banNoticeDiv = document.getElementById("banNoticeDiv");
        banNoticeDiv.appendChild(notice);
    } else if (banStatus === "Unbanned" && window.location.pathname.endsWith("banned.php")) {
        window.location.href = "home.php";
    }
});