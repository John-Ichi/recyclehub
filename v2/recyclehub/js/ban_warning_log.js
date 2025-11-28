const warningNoticeModal = document.getElementById("warningNoticeModal");
const warningNoticeDiv = document.getElementById("warningNoticeDiv");

fetch("warning_logs.json?nocache=" + new Date().getTime())
.then(res => res.json())
.then(data => {
    if (data === null) {
        return;
    }

    if (document.getElementById("imageUploadUserId")) {
        userId = document.getElementById("imageUploadUserId").value;
    } else {
        userId = document.getElementById("userId").value;
    }

    const userWarningLogs = data.filter(log => log.userId === userId);

    if (userWarningLogs.length === 0) {
        return;
    }

    let warningSummary = "";

    userWarningLogs.forEach(log => {
        if (log.confirmed === "0") {
            warningSummary += `Warning: ${log.warningMessage}\n`;
        }
    });

    if (warningSummary !== "") {
        const notice = document.createElement("div");
        notice.classList.add("notice");

        const message = document.createElement("p");
        message.classList.add("noticeMessage");
        message.innerHTML = `Your accounts is in risk of a potential ban!<br>Summary:<br>${warningSummary}`;

        const confirmationForm = document.createElement("form");
        confirmationForm.action = "functions.php";
        confirmationForm.method = "POST"

        const userIdInput = document.createElement("input");
        userIdInput.type = "hidden";
        userIdInput.name = "user_id";
        userIdInput.value = userId;

        const confirmNotice = document.createElement("input");
        confirmNotice.type = "hidden";
        confirmNotice.name = "confirm_ban_warning";
        confirmNotice.value = "true";

        const confirmBtn = document.createElement("button");
        confirmBtn.type = "submit";
        confirmBtn.textContent = "Confirm";

        confirmationForm.appendChild(userIdInput);
        confirmationForm.appendChild(confirmNotice);
        confirmationForm.appendChild(confirmBtn);

        notice.appendChild(message);
        notice.appendChild(confirmationForm);
        warningNoticeDiv.appendChild(notice);

        warningNoticeModal.style.display = "block";

        confirmationForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = new FormData(confirmationForm);

            var confirmXhttp = new XMLHttpRequest();
            confirmXhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var reloadXhttp = new XMLHttpRequest()
                    reloadXhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            warningNoticeModal.style.display = "none";
                            warningNoticeDiv.innerHTML = "";
                        }
                    }
                    reloadXhttp.open("GET", "home.php", true);
                    reloadXhttp.send();
                }
            }
            confirmXhttp.open("POST", "functions.php", true);
            confirmXhttp.send(formData);
        });
    }
});