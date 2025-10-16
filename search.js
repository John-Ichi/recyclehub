// Clear URL after reloading
let url = new URL(window.location.href);
url.search = "";

if (performance.getEntriesByType("navigation")[0].type === "reload") {
    window.history.replaceState(null, null, url);
}

const usersDiv = document.querySelector(".users"); // Referencing users div

const searchBar = document.getElementById("searchBar");
let searchBarInput = ""; 

const homeSearch = new URLSearchParams(window.location.search);
let homeSearchInput = homeSearch.get("searchUser"); // Search input from home.php
searchBar.value = homeSearchInput; // Insert input from home.php into search.php search bar

const noUsersMessage = document.createElement("p"); // No user(s) found message
noUsersMessage.textContent = "No user(s) found.";

fetch("users.json") // Fetch all users
.then(res => res.json())
.then(data => {
    const currentUserId = document.getElementById("userId").value; // Reference current user ID (ID of logged in user)
    let filteredUsers = []; // Initialize filteredUsers array

    searchBar.addEventListener("input", () => { // Check for search inputs
        searchBarInput = searchBar.value.trim().toLowerCase(); // Case sensitivity

        filteredUsers = data.filter(user => user.username.trim().toLowerCase().includes(searchBarInput)); // Case-insensitive filter

        if (searchBarInput !== "") { // Check if there are inputs in the search bar
            if (filteredUsers.length === 0) { // No matches
                usersDiv.innerHTML = "";
                usersDiv.appendChild(noUsersMessage); // Show no user(s) found
            } else {
                renderUsers(filteredUsers); // Else render filtered users
                redirectCurrentUserToProfile(currentUserId);
            }
        } else {
            renderUsers(data); // Render all users when search bar is cleared
            redirectCurrentUserToProfile(currentUserId);
        }
    });

    if (homeSearchInput !== null) { // Search from home
        homeSearchInput = homeSearchInput.trim().toLowerCase(); // Case sensitivity
        
        filteredUsers = data.filter(user => user.username.trim().toLowerCase().includes(homeSearchInput)); // Case-insensitive filter

        if (filteredUsers.length === 0) { // If no users found
            usersDiv.innerHTML = "";
            usersDiv.appendChild(noUsersMessage); // Show no user(s) found
        } else {
            renderUsers(filteredUsers); // Render filtered users
            redirectCurrentUserToProfile(currentUserId);
        }
    } else {
        renderUsers(data); // Render all users if there is no search input from home.php
        redirectCurrentUserToProfile(currentUserId);
    }

    redirectCurrentUserToProfile(currentUserId);

    const followForms = document.querySelectorAll(".followForm");
    followForms.forEach(form => {
        form.addEventListener("submit", (e) => {
            e.preventDefault();

            const formData = new FormData(form)

            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "follow.php");
            xhttp.send(formData);
        });
    });
});

function renderUsers(users) {
    usersDiv.innerHTML = ""; // Clear users div

    users.forEach(user => { // For each user
        const userDiv = document.createElement("div"); // Create a div
        userDiv.classList.add("user"); // With class "user"

        const username = document.createElement("a"); // Create an href a element
        username.classList.add("username"); // With class "username"
        username.id = user.userId;
        username.textContent = user.username;
        username.href = `user.php?user=${user.userId}`; // URL
        username.target = "_blank"; // New page

        const followForm = document.createElement("form");
        followForm.classList.add("followForm");
        followForm.method = "POST";

        const followeeInput = document.createElement("input");
        followeeInput.type = "text";
        followeeInput.name = "followee_id";
        followeeInput.value = user.userId;

        const followerInput = document.createElement("input");
        followerInput.type = "text";
        followerInput.name = "follower_id";
        followerInput.value = document.getElementById("userId").value;

        const followButton = document.createElement("button");
        followButton.name = "follow_user";
        followButton.textContent = "Follow";

        followForm.appendChild(followeeInput);
        followForm.appendChild(followerInput);
        followForm.appendChild(followButton);
        
        userDiv.appendChild(username);
        userDiv.appendChild(followForm);
        usersDiv.appendChild(userDiv);
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