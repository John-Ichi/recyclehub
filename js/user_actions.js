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

    deletePostForm.addEventListener("submit", (e) => {
        // e.preventDefault();
    })
});

function renderUserData(users) {
    const userData = users.filter(user => user.userId === urlParamsVal);

    document.getElementById("username").innerHTML = userData[0].username;
    document.getElementById("userId").value = userData[0].userId;
    document.getElementById("userEmail").innerHTML = userData[0].userEmail;
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
            deletePostModal.querySelector("#userId").value = group[0].userId;
            document.getElementById("postId").value = postId;
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