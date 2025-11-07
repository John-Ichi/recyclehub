const urlParams = new URLSearchParams(window.location.search);
const urlParamsVal = urlParams.get("user");

const filterSelection = document.querySelectorAll(".filterPosts");
let filterArray = [];

const postsDiv = document.querySelector(".posts");

const commentModal = document.getElementById("commentModal");
const closeCommentModalBtn = commentModal.querySelector(".close");
const commentsDiv = document.getElementById("comments");

closeCommentModalBtn.addEventListener("click", () => {
    commentModal.style.display = "none";
    commentsDiv.innerHTML = "";
});

const deletePostModal = document.getElementById("deletePostModal");
const closeDeletePostModalBtn = deletePostModal.querySelector(".close");
const deletePostForm = deletePostModal.querySelector("form");

closeDeletePostModalBtn.addEventListener("click", () => {
    deletePostModal.style.display = "none";
    deletePostForm.reset();
});

const warningBtn = document.getElementById("warningBtn");
const warningModal = document.getElementById("warningModal");
const closeWarningModalBtn = warningModal.querySelector(".close");
const warningForm = warningModal.querySelector("form");

warningBtn.addEventListener("click", () => {
    warningModal.style.display = "block";
})

closeWarningModalBtn.addEventListener("click", () => {
    warningModal.style.display = "none";
    warningForm.reset();
});

const banBtn = document.getElementById("banBtn");
const banUserModal = document.getElementById("banUserModal");
const closeBanModal = banUserModal.querySelector(".close");
const banForm = banUserModal.querySelector("form");

banBtn.addEventListener("click", () => {
    banUserModal.style.display = "block";
});

closeBanModal.addEventListener("click", () => {
    banUserModal.style.display = "none";
    banForm.reset();
});

window.addEventListener("keydown", (e) => { // Extra
    if (e.key === "Escape") {
        if (commentModal.style.display === "block") {
            commentModal.style.display = "none";
            commentsDiv.innerHTML = "";
        }
        if (deletePostModal.style.display === "block") {
            deletePostModal.style.display = "none";
            deletePostForm.reset();
        }
        if (warningModal.style.display === "block") {
            warningModal.style.display = "none";
            warningForm.reset();
        }
        if (banUserModal.style.display === "block") {
            banUserModal.style.display = "none";
            banForm.reset();
        }
    }
});

fetch("users.json")
.then(res => res.json())
.then(data => {
    renderUserData(data);
});

fetch("posts.json")
.then(res => res.json())
.then(data => {
    if (data === null) {
        postsDiv.innerHTML = "No post(s) yet.";
        return;
    }
    
    const userId = urlParamsVal;

    const filteredPostsByUserId = data.filter(post => post.userId === userId);

    if (filteredPostsByUserId.length === 0) {
        postsDiv.innerHTML = "No post(s) yet.";
        return;
    }

    filterSelection.forEach(btn => {
        btn.addEventListener("change", () => {
            if (btn.checked) {
                filterArray.push(btn.value);
            } else {
                let index = filterArray.indexOf(btn.value);
                filterArray.splice(index, 1);
            }

            if (filterArray.length === 0) {
                renderPosts(filteredPostsByUserId);
                return;
            }

            const filteredPosts = filteredPostsByUserId.filter(post => filterArray.includes(post.category));
            renderPosts(filteredPosts);
        });
        renderPosts(filteredPostsByUserId);
    });
});

function renderUserData(users) {
    const userDiv = document.querySelector(".userInfo");
    const userData = users.filter(user => user.userId === urlParamsVal);
    const userId = userData[0].userId;

    document.getElementById("username").innerHTML = userData[0].username;
    document.getElementById("userId").value = userData[0].userId;
    document.getElementById("userEmail").innerHTML = userData[0].userEmail;
    warningForm.querySelector(".userId").value = userId;
    banForm.querySelector(".userId").value = userId;

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

                let numOfDeletedPosts = 0;

                if (deletedPostLogs !== null) {
                    const filteredDeletedLogs = deletedPostLogs.filter(log => log.userId === userId);
                    filteredDeletedLogs.forEach(log => {
                        numOfDeletedPosts += 1;
                    });
                }

                let numOfWarnings = 0;
                let numOfConfirmedWarnings = 0;

                if (warningLogs !== null) {
                    const filteredWarningLogs = warningLogs.filter(log => log.userId === userId);

                    if (filteredWarningLogs.length !== 0) {
                        numOfWarnings = filteredWarningLogs.length;
                    }

                    filteredWarningLogs.forEach(log => {
                        if (log.confirmed === "1") {
                            numOfConfirmedWarnings += 1;
                        }
                    })
                }

                let banStatus = "Unbanned";
                let banHistory = 0;

                if (banLogs !== null) {
                    const filteredBanLogs = banLogs.filter(log => log.userId === userId);
                    filteredBanLogs.forEach(log => {
                        banHistory += 1;
                        
                        if (log.unban === "0") {
                            banStatus = "Banned";
                        }
                    });
                }

                if (banStatus === "Banned") {
                    banBtn.textContent = "Unban User";

                    const modalHeader = banUserModal.querySelector("h2");
                    modalHeader.textContent = "Unban User";

                    const modalMessage = banUserModal.querySelector("p");
                    modalMessage.textContent = "Enter admin username and password to unban user.";

                    const banInput = banForm.querySelector(".banInput");
                    banInput.name = "unban_user";

                    const adminUserInput = document.createElement("input");
                    adminUserInput.type = "text";
                    adminUserInput.name = "admin_username";
                    adminUserInput.placeholder = "Username";
                    adminUserInput.required = true;

                    const passwordInput = document.createElement("input");
                    passwordInput.type = "password"; // Change to password
                    passwordInput.name = "admin_password";
                    passwordInput.placeholder = "Password";
                    passwordInput.required = true;

                    const referenceElement = banForm.querySelector("button");

                    banForm.removeChild(banForm.querySelector("textarea"));
                    banForm.insertBefore(adminUserInput, referenceElement);
                    banForm.insertBefore(passwordInput, referenceElement);
                }

                const numOfDeletedPostsIndicator = document.createElement("p");
                numOfDeletedPostsIndicator.classList.add("numOfDeletedPosts");
                numOfDeletedPostsIndicator.innerHTML = `Number of deleted posts: ${numOfDeletedPosts}`;

                const numOfWarningsIndicator = document.createElement("p");
                numOfWarningsIndicator.classList.add("numOfWarnings");
                numOfWarningsIndicator.innerHTML = `Number of warnings: ${numOfWarnings}`;

                const numOfConfirmedWarningsIndicator = document.createElement("p");
                numOfConfirmedWarningsIndicator.classList.add("numOfConfirmedWarnings");
                numOfConfirmedWarningsIndicator.innerHTML = `Number of confirmed warnings: ${numOfConfirmedWarnings}`;
                
                const banStatusIndicator = document.createElement("p");
                banStatusIndicator.classList.add("banStatus");
                banStatusIndicator.innerHTML = `Ban status: ${banStatus}`;

                const numOfBans = document.createElement("p");
                numOfBans.classList.add("numOfBans");
                numOfBans.innerHTML = `Number of bans received: ${banHistory}`;

                userDiv.appendChild(numOfDeletedPostsIndicator);
                userDiv.appendChild(numOfWarningsIndicator);
                userDiv.appendChild(numOfConfirmedWarningsIndicator);
                userDiv.appendChild(banStatusIndicator);
                userDiv.appendChild(numOfBans);
            });
        });
    });
}

function renderPosts(posts) {
    postsDiv.innerHTML = "";

    const groupedPosts = posts.reduce((groupedPostsArray, post) => {
        if (!groupedPostsArray[post.postId]) {
            groupedPostsArray[post.postId] = [];
        }
        groupedPostsArray[post.postId].push(post);
        return groupedPostsArray;
    }, {});

    Object.entries(groupedPosts).forEach(([id, group]) => {
        const innerPostDiv = document.createElement("div");
        const postId = group[0].postId;
        innerPostDiv.id = postId;
        innerPostDiv.classList.add("post");

        const postUsername = document.createElement("h4");
        postUsername.textContent = group[0].username;
        innerPostDiv.appendChild(postUsername);

        const deletePostBtn = document.createElement("button");
        deletePostBtn.textContent = "Delete";

        deletePostBtn.addEventListener("click", () => {
            deletePostModal.style.display = "block";
            deletePostForm.querySelector(".userId").value = group[0].userId;
            deletePostForm.querySelector(".postId").value = postId;
        });

        innerPostDiv.appendChild(deletePostBtn);

        const caption = document.createElement("p");
        caption.textContent = group[0].content || "";

        group.forEach(post => {
            if (post.image != null) {
                const img = document.createElement("img");
                img.src = post.image;
                innerPostDiv.appendChild(img);
            }
        });
        
        if (caption.textContent != "") {
            innerPostDiv.appendChild(caption);
        }

        const viewCommentsBtn = document.createElement("button");
        viewCommentsBtn.textContent = "View Comments";

        viewCommentsBtn.addEventListener("click", () => {
            commentModal.style.display = "block";
            renderComments(postId);
        });

        innerPostDiv.appendChild(viewCommentsBtn);
        postsDiv.appendChild(innerPostDiv);
    });
}

function renderComments(postId) {
    fetch("comments.json?nocache=" + new Date().getTime())
    .then(res => res.json())
    .then(data => {
        if (data === null) {
            commentsDiv.innerHTML = "No comment(s) yet.";
            return;
        }

        const postComments = data.filter(comment => comment.postId == postId);

        commentsDiv.innerHTML = "";

        postComments.forEach(comment => {
            const commentContainer = document.createElement("div");
            commentContainer.classList.add("comment");

            const commenter = document.createElement("h4");
            commenter.textContent = comment.username;

            const commentContent = document.createElement("p");
            commentContent.textContent = comment.commentContent;

            commentContainer.appendChild(commenter);
            commentContainer.appendChild(commentContent);

            commentsDiv.appendChild(commentContainer);
        });

        if (postComments.length === 0) {
            const noCommentsMsg = document.createElement("p");
            noCommentsMsg.textContent = "No comment(s) yet.";

            commentsDiv.appendChild(noCommentsMsg);
        }
    })
}