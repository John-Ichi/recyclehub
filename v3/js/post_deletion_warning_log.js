const postDeletionNoticeModal = document.getElementById("postDeletionNoticeModal");
const postDeletionNoticeDiv = document.getElementById("noticeDiv");

fetch("deleted_logs.json?nocache=" + new Date().getTime())
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
    const userLogs = data.filter(log => log.userId === userId);

    if (userLogs.length === 0) {
        return;
    }

    let postSummary = "";

    userLogs.forEach(log => {
        if (log.confirmed === "0") {
            postSummary += (`Caption: ${log.content}; Reason: ${log.purposeOfDeletion}.<br>`);
        }
    });

    if (postSummary !== "") {
        const notice = document.createElement("div");
        notice.classList.add("notice");

        const message = document.createElement("p");
        message.classList.add("noticeMessage");
        message.innerHTML = `Your recent post(s) has been deleted:<br>${postSummary}`;

        const confirmationForm = document.createElement("form");
        confirmationForm.action = "functions.php";
        confirmationForm.method = "POST";

        const userIdInput = document.createElement("input");
        userIdInput.type = "hidden";
        userIdInput.name = "user_id";
        userIdInput.value = userId;

        const confirmNotice = document.createElement("input");
        confirmNotice.type = "hidden";
        confirmNotice.name = "confirm_notice";
        confirmNotice.value = "true";

        const confirmBtn = document.createElement("button");
        confirmBtn.type = "submit";
        confirmBtn.textContent = "Confirm";

        confirmationForm.appendChild(userIdInput);
        confirmationForm.appendChild(confirmNotice);
        confirmationForm.appendChild(confirmBtn);

        notice.appendChild(message);
        notice.appendChild(confirmationForm);
        postDeletionNoticeDiv.appendChild(notice);

        postDeletionNoticeModal.style.display = "block";

        confirmationForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = new FormData(confirmationForm);

            var confirmXhttp = new XMLHttpRequest();
            confirmXhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var reloadXhttp = new XMLHttpRequest();
                    reloadXhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            postDeletionNoticeModal.style.display = "none";
                            postDeletionNoticeDiv.innerHTML = "";
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
})