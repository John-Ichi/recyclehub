const urlParams = new URLSearchParams(window.location.search);
let urlParamsVal = urlParams.get("user");

const formDiv = document.querySelector(".searchUserFollowActions");

fetch("users.json?nocache=" + new Date().getTime())
.then(res => res.json())
.then(data => {
    renderSearchUserInfo(data);

    formDiv.addEventListener("submit", (e) => {
        e.preventDefault();

        const form = e.target.closest(".followForm, .unfollowForm");
        if (form === null) {
            return;
        }

        const formData = new FormData(form);

        var followXhttp = new XMLHttpRequest();
        followXhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (form.firstChild.value === this.responseText) {
                    if (form.classList == "followForm") {
                        form.classList.replace("followForm", "unfollowForm");

                        const submitInput = form.querySelector(".submitInput");
                        submitInput.name = "unfollow_user";

                        const followButton = form.querySelector(".follow");
                        followButton.classList.replace("follow", "unfollow");
                        followButton.textContent = "Unfollow";
                    } else if (form.classList == "unfollowForm") {
                        form.classList.replace("unfollowForm", "followForm");
                        
                        const submitInput = form.querySelector(".submitInput");
                        submitInput.name = "follow_user";

                        const unfollowButton = form.querySelector(".unfollow");
                        unfollowButton.classList.replace("unfollow", "follow");
                        unfollowButton.textContent = "Follow";
                    }
                }
                var updateXhttp = new XMLHttpRequest();
                updateXhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        fetch("user_info.json?nocache=" + new Date().getTime())
                        .then(res => res.json())
                        .then(data => {
                            let usersFollowed = [];

                            data.forEach(user => {
                                usersFollowed.push(user.followee);
                            });

                            window.usersFollowed = data.map(u => u.followee);
                        });
                    }
                }
                updateXhttp.open("GET", "search.php", true);
                updateXhttp.send();
            }
        }
        followXhttp.open("POST", "follow.php", true);
        followXhttp.send(formData);
    });
});

function renderSearchUserInfo(searchUserData) {
    const userData = searchUserData.filter(user => user.userId === urlParamsVal);
    const currentUserId = document.getElementById("userId").value;

    formDiv.innerHTML = "";

    document.getElementById("searchUsername").innerHTML = userData[0].username;
    document.getElementById("searchUserId").value = userData[0].userId;
    document.getElementById("searchUserEmail").innerHTML = userData[0].userEmail;

    const followeeInput = document.createElement("input");
    followeeInput.classList.add("followeeInput");
    followeeInput.type = "hidden";
    followeeInput.name = "followee_id";
    followeeInput.value = userData[0].userId;

    const followerInput = document.createElement("input");
    followerInput.classList.add("followerInput");
    followerInput.type = "hidden";
    followerInput.name = "follower_id";
    followerInput.value = currentUserId;

    const followUser = document.createElement("input");
    followUser.classList.add("submitInput");
    followUser.type = "hidden";
    followUser.name = "follow_user";
    followUser.value = "true";

    const unfollowUser = document.createElement("input");
    unfollowUser.classList.add("submitInput");
    unfollowUser.type = "hidden";
    unfollowUser.name = "unfollow_user";
    unfollowUser.value = "true";

    const followButton = document.createElement("button");
    followButton.classList.add("follow");
    followButton.classList.add("follow-btn");
    followButton.id = "followButton";
    followButton.type = "submit";
    followButton.textContent = "Follow";

    const unfollowButton = document.createElement("button");
    unfollowButton.classList.add("unfollow");
    unfollowButton.classList.add("follow-btn");
    unfollowButton.id = "followButton";
    unfollowButton.type = "submit";
    unfollowButton.textContent = "Unfollow";

    const usersFollowedBySession = window.usersFollowed;

    if (usersFollowedBySession.includes(userData[0].userId)) {
        const unfollowForm = document.createElement("form");
        unfollowForm.classList.add("unfollowForm");
        unfollowForm.action = "follow.php";
        unfollowForm.method = "POST";

        unfollowForm.appendChild(followeeInput);
        unfollowForm.appendChild(followerInput);
        unfollowForm.appendChild(unfollowUser);
        unfollowForm.appendChild(unfollowButton);
        formDiv.appendChild(unfollowForm);
    } else {
        const followForm = document.createElement("form");
        followForm.classList.add("followForm");
        followForm.action = "follow.php";
        followForm.method = "POST";

        followForm.appendChild(followeeInput);
        followForm.appendChild(followerInput);
        followForm.appendChild(followUser);
        followForm.appendChild(followButton);
        formDiv.appendChild(followForm);
    }

    fetch("ban_logs.json")
    .then(res => res.json())
    .then(data => {
        const banLogs = data;

        const activeBan = banLogs.find(log =>
            log.userId === userData[0].userId && log.unban === "0"
        );

        if (activeBan) {
            formDiv.innerHTML = "";
        }
    });
}