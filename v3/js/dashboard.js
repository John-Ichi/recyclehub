const usersDiv = document.querySelector(".users");

const searchBar = document.getElementById("searchBar");
let searchBarInput = "";

const noUsersMessage = document.createElement("p");
noUsersMessage.textContent = "No user(s) found.";

fetch("users.json")
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
    renderUsers(data);
});

function renderUsers(users) {
    fetch("deleted_logs.json")
    .then(res => res.json())
    .then(data => {
        const deletedPostLogs = data;

        fetch("warning_logs.json")
        .then(res => res.json())
        .then(data => {
            const warningLogs = data;

            fetch("ban_logs.json")
            .then(res => res.json())
            .then(data => {
                const banLogs = data;

                usersDiv.innerHTML = "";

                users.forEach(user => {
                    const userDiv = document.createElement("div");
                    userDiv.classList.add("user");
                    userDiv.id = user.userId;

                    const username = document.createElement("a");
                    username.classList.add("username");
                    username.id = user.userId;
                    username.href = `user_actions.php?user=${user.userId}`;
                    username.textContent = user.username;

                    let numOfWarnings = 0;
                    let numOfConfirmedWarnings = 0;

                    if (warningLogs !== null) {
                        const filteredWarningLogs = warningLogs.filter(log => log.userId === user.userId);

                        if (filteredWarningLogs.length !== 0) {
                            numOfWarnings = filteredWarningLogs.length;
                        }

                        filteredWarningLogs.forEach(log => {
                            if (log.confirmed === "1") {
                                numOfConfirmedWarnings += 1;
                            }
                        })
                    }

                    let numOfDeletedPosts = 0;

                    if (deletedPostLogs !== null) {
                        const filteredDeletedLogs = deletedPostLogs.filter(log => log.userId === user.userId);
                        filteredDeletedLogs.forEach(log => {
                            numOfDeletedPosts += 1;
                        });
                    }

                    let banStatus = "Unbanned";
                    let banHistory = 0;

                    if (banLogs !== null) {
                        const filteredBanLogs = banLogs.filter(log => log.userId === user.userId);
                        filteredBanLogs.forEach(log => {
                            banHistory += 1;

                            if (log.unban === "0") {
                                banStatus = "Banned";
                            }
                        });
                    }

                    const numOfWarningsIndicator = document.createElement("p");
                    numOfWarningsIndicator.classList.add("numOfWarnings");
                    numOfWarningsIndicator.innerHTML = `Number of warnings: ${numOfWarnings}`;

                    const numOfConfirmedWarningsIndicator = document.createElement("p");
                    numOfConfirmedWarningsIndicator.classList.add("numOfConfirmedWarnings");
                    numOfConfirmedWarningsIndicator.innerHTML = `Number of confirmed warnings: ${numOfConfirmedWarnings}`;

                    const numOfDeletedPostsIndicator = document.createElement("p");
                    numOfDeletedPostsIndicator.classList.add("numOfDeletedPosts");
                    numOfDeletedPostsIndicator.innerHTML = `Number of deleted posts: ${numOfDeletedPosts}`;
                    
                    const banStatusIndicator = document.createElement("p");
                    banStatusIndicator.classList.add("banStatus");
                    banStatusIndicator.innerHTML = `Ban status: ${banStatus}`;

                    const numOfBans = document.createElement("p");
                    numOfBans.classList.add("numOfBans");
                    numOfBans.innerHTML = `Number of bans received: ${banHistory}`;

                    userDiv.appendChild(username);
                    userDiv.appendChild(numOfWarningsIndicator);
                    userDiv.appendChild(numOfConfirmedWarningsIndicator);
                    userDiv.appendChild(numOfDeletedPostsIndicator);
                    userDiv.appendChild(banStatusIndicator);
                    userDiv.appendChild(numOfBans);
                    usersDiv.appendChild(userDiv);
                });
            });
        });
    });
}