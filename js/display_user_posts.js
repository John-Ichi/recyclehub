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

let filterArray = [];

fetch("posts.json")
.then(res => res.json())
.then(data => {
    if (data === null) {
        postsDiv.innerHTML = "No post(s) yet.";
        return;
    }

    let userId;

    if (document.getElementById("searchUserId") === null) {
        userId = document.getElementById("userId").value;
        document.getElementById("imageUploadUserId").value = userId;
    } else {
        userId = document.getElementById("searchUserId").value;
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
            renderPosts(filteredPosts);
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

        const commentBtn = document.createElement("button"); // Create comment button
        commentBtn.textContent = "Comment";

        commentBtn.addEventListener("click", () => {
            commentModal.style.display = "block"; // Open comment modal
            postCommentsDiv.innerHTML = ""; // Clear div

            const commentForm = document.createElement("form"); // Create form for posting comments
            commentForm.id = "commentForm";
            commentForm.action = "functions.php";
            commentForm.method = "POST";

            const postIdField = document.createElement("input"); // Input post ID
            postIdField.type = "hidden";
            postIdField.name = "post_id";
            postIdField.value = postId;

            const commenterField = document.createElement("input"); // Input user ID
            commenterField.type = "hidden";
            commenterField.name = "commenter";
            commenterField.value = document.getElementById("userId").value;

            const commentField = document.createElement("textarea"); // Input for comment
            commentField.name = "comment";
            commentField.maxLength = 250;

            const postComment = document.createElement("input"); // Extra validation
            postComment.type = "hidden";
            postComment.name = "post_comment";
            postComment.value = "true";

            const submitComment = document.createElement("button"); // Submit
            submitComment.type = "submit";
            submitComment.textContent = "Comment";

            commentForm.addEventListener("submit", (e) => { // When submitted
                e.preventDefault(); // Prevent reload/redirect

                if (commentField.value === "") { // If empty
                    return;
                } else if (commentField.value !== "") {
                    const formData = new FormData(commentForm);

                    var commentXhttp = new XMLHttpRequest(); // Post comment
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
    const commentForm = document.getElementById("commentForm"); // Select comment form
    const postId = commentForm.firstChild.value; // Get post ID via comment form

    fetch("comments.json?nocache=" + new Date().getTime()) // Get all comments
    .then(res => res.json())
    .then(data => {
        const postComments = data.filter(comment => comment.postId == postId); // Filter comments by post ID

        commentsDiv.innerHTML = "";

        postComments.forEach(comment => { // For each comment
            const commentContainer = document.createElement("div"); // Create a comment container
            commentContainer.classList.add("comment");

            const commenter = document.createElement("h4"); // Add commenter username
            commenter.textContent = comment.username;

            const commentContent = document.createElement("p"); // Add comment
            commentContent.textContent = comment.commentContent;

            commentContainer.appendChild(commenter);
            commentContainer.appendChild(commentContent);

            commentsDiv.appendChild(commentContainer);
        });
    });
}