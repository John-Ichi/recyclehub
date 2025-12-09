let url = new URL(window.location.href);
url.search = "";

if (performance.getEntriesByType("navigation")[0].type === "reload") {
    window.history.replaceState(null, null, url);
}

const usersDiv = document.querySelector(".users");

const searchBar = document.getElementById("searchBar");
let searchBarInput = ""; 

const homeSearch = new URLSearchParams(window.location.search);
let homeSearchInput = homeSearch.get("searchUser");
searchBar.value = homeSearchInput;

const noUsersMessage = document.createElement("p");
noUsersMessage.textContent = "No user(s) found.";

fetch("users.json?nocache=" + new Date().getTime())
.then(res => res.json())
.then(data => {
    let filteredUsers = [];

    searchBar.addEventListener("input", () => {
        searchBarInput = searchBar.value.trim().toLowerCase();

        filteredUsers = data.filter(user => user.username.trim().toLowerCase().includes(searchBarInput));

        if (searchBarInput !== "") {
            if (filteredUsers.length === 0) {
                usersDiv.innerHTML = "";
                usersDiv.appendChild(noUsersMessage);
            } else {
                renderUsers(filteredUsers);
            }
        } else {
            renderUsers(data);
        }
    });

    if (homeSearchInput !== null) {
        homeSearchInput = homeSearchInput.trim().toLowerCase();
        
        filteredUsers = data.filter(user => user.username.trim().toLowerCase().includes(homeSearchInput));

        if (filteredUsers.length === 0) {
            usersDiv.innerHTML = "";
            usersDiv.appendChild(noUsersMessage);
        } else {
            renderUsers(filteredUsers);
        }
    } else {
        renderUsers(data);
    }

    usersDiv.addEventListener("submit", (e) => {
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

function renderUsers(users) {
    usersDiv.innerHTML = "";

    const currentUserId = document.getElementById("userId").value;

    users.forEach(user => {
        const userDiv = document.createElement("div");
        userDiv.classList.add("user");
        userDiv.id = user.userId;

        const username = document.createElement("a");
        username.classList.add("username");
        username.id = user.userId;
        username.href = `user.php?user=${user.userId}`;
        username.target = "_blank";
        username.textContent = user.username;

        userDiv.appendChild(username);

        const followeeInput = document.createElement("input");
        followeeInput.classList.add("followeeInput");
        followeeInput.type = "text";
        followeeInput.name = "followee_id";
        followeeInput.value = user.userId;

        const followerInput = document.createElement("input");
        followerInput.classList.add("followerInput");
        followerInput.type = "text";
        followerInput.name = "follower_id";
        followerInput.value = currentUserId;

        const followUser = document.createElement("input");
        followUser.classList.add("submitInput");
        followUser.type = "text";
        followUser.name = "follow_user";
        followUser.value = "true";

        const unfollowUser = document.createElement("input");
        unfollowUser.classList.add("submitInput");
        unfollowUser.type = "text";
        unfollowUser.name = "unfollow_user";
        unfollowUser.value = "true";

        const followButton = document.createElement("button");
        followButton.classList.add("follow");
        followButton.type = "submit";
        followButton.textContent = "Follow";

        const unfollowButton = document.createElement("button");
        unfollowButton.classList.add("unfollow");
        unfollowButton.type = "submit";
        unfollowButton.textContent = "Unfollow";

        const usersFollowed = window.usersFollowed;

        if (usersFollowed.includes(user.userId)) {
            const unfollowForm = document.createElement("form");
            unfollowForm.classList.add("unfollowForm");
            unfollowForm.action = "follow.php";
            unfollowForm.method = "POST";

            unfollowForm.appendChild(followeeInput);
            unfollowForm.appendChild(followerInput);
            unfollowForm.appendChild(unfollowUser);
            unfollowForm.appendChild(unfollowButton);
            userDiv.appendChild(unfollowForm);
        } else {
            const followForm = document.createElement("form");
            followForm.classList.add("followForm");
            followForm.action = "follow.php";
            followForm.method = "POST";

            followForm.appendChild(followeeInput);
            followForm.appendChild(followerInput);
            followForm.appendChild(followUser);
            followForm.appendChild(followButton);

            if (userDiv.firstChild.id !== currentUserId) {
                userDiv.appendChild(followForm);
            }
        }
        usersDiv.appendChild(userDiv);

        fetch("ban_logs.json")
        .then(res => res.json())
        .then(data => {
            const banLogs = data;

            const activeBan = banLogs.find(log =>
                log.userId === user.userId && log.unban === "0"
            );

            if (activeBan) {
                if (userDiv.querySelector(".followForm")) {
                    userDiv.removeChild(userDiv.querySelector(".followForm"));
                } else if (userDiv.querySelector(".unfollowForm")) {
                    userDiv.removeChild(userDiv.querySelector(".unfollowForm"));
                }
            }
        });

        redirectCurrentUserToProfile(currentUserId);
    });
}

function redirectCurrentUserToProfile(userId) {
    const users = document.querySelectorAll(".username");
    users.forEach(user => {
        if (user.id === userId) {
            user.href = "profile.php";
        }
    });
}