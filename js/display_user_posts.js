const filterSelection = document.querySelectorAll(".filterPosts");
const postsDiv = document.querySelector(".posts");

const commentModal = document.getElementById("commentModal");
const closeCommentModalBtn = commentModal.querySelector(".close");
const commentsDiv = document.getElementById("comments");
const postCommentsDiv = document.getElementById("postComments");

closeCommentModalBtn.addEventListener("click", () => {
    commentModal.style.display = "none";
    commentsDiv.innerHTML = "";
    postCommentsDiv.innerHTML = "";
});

window.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        if (commentModal.style.display === "block") {
            commentModal.style.display = "none";
            commentsDiv.innerHTML = "";
            postCommentsDiv.innerHTML = "";
        }
    }
});

let filterArray = [];

fetch("posts.json?nocache=" + new Date().getTime())
.then(res => res.json())
.then(data => {
    if (data === null) {
        postsDiv.innerHTML = "No post(s) yet.";
        return;
    }

    let userId;

    if (document.getElementById("searchUserId")) {
        userId = document.getElementById("searchUserId").value;
    } else {
        userId = document.getElementById("userId").value;
        document.getElementById("imageUploadUserId").value = userId;
    }

    const filterPostsByUserId = data.filter(post => post.userId === userId);

    if (filterPostsByUserId.length === 0) {
        postsDiv.innerHTML = "No post(s) yet."
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
                renderPosts(filterPostsByUserId);
                return;
            }
            const filteredPosts = filterPostsByUserId.filter(post => filterArray.includes(post.category));
            if (filteredPosts.length === 0) {
                postsDiv.innerHTML = "No post(s) to see.";
                return;
            } else {
                renderPosts(filteredPosts);
            }
        });
        renderPosts(filterPostsByUserId);
    });
});

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

        const commentBtn = document.createElement("button");
        commentBtn.textContent = "Comment";

        commentBtn.addEventListener("click", () => {
            commentModal.style.display = "block";
            postCommentsDiv.innerHTML = "";

            const commentForm = document.createElement("form");
            commentForm.id = "commentForm";
            commentForm.action = "functions.php";
            commentForm.method = "POST";

            const postIdField = document.createElement("input");
            postIdField.type = "hidden";
            postIdField.name = "post_id";
            postIdField.value = postId;

            const commenterField = document.createElement("input");
            commenterField.type = "hidden";
            commenterField.name = "commenter";
            commenterField.value = document.getElementById("userId").value;

            const commentField = document.createElement("textarea");
            commentField.name = "comment";
            commentField.maxLength = 250;

            const postComment = document.createElement("input");
            postComment.type = "hidden";
            postComment.name = "post_comment";
            postComment.value = "true";

            const submitComment = document.createElement("button");
            submitComment.type = "submit";
            submitComment.textContent = "Comment";

            commentForm.addEventListener("submit", (e) => {
                e.preventDefault();

                if (commentField.value === "") {
                    return;
                } else if (commentField.value !== "") {
                    const formData = new FormData(commentForm);

                    var commentXhttp = new XMLHttpRequest();
                    commentXhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            commentField.value = "";
                            renderComments();
                        }
                    }
                    commentXhttp.open("POST", "functions.php", true);
                    commentXhttp.send(formData);
                }
            });
            commentForm.appendChild(postIdField);
            commentForm.appendChild(commenterField);
            commentForm.appendChild(commentField);
            commentForm.appendChild(postComment);
            commentForm.appendChild(submitComment);
            postCommentsDiv.appendChild(commentForm);
            renderComments();
        });
        innerPostDiv.appendChild(commentBtn);
        postsDiv.appendChild(innerPostDiv);
    });
}

function renderComments() {
    const commentForm = document.getElementById("commentForm");
    const postId = commentForm.firstChild.value;

    fetch("comments.json?nocache=" + new Date().getTime())
    .then(res => res.json())
    .then(data => {
        if (data === null) {
            commentsDiv.innerHTML = "No comment(s) yet.";
            return;
        }

        const postComments = data.filter(comment => comment.postId == postId);

        if (postComments.length === 0) {
            commentsDiv.innerHTML = "No comment(s) yet.";
            return;
        }

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
    });
}