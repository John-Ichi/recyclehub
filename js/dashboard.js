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
})

function renderUsers(users) {
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

        userDiv.appendChild(username);
        usersDiv.appendChild(userDiv);
    });
}