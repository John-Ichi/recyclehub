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

fetch("posts.json")
.then(res => res.json())
.then(data => {
    if (data === null) {
        postsDiv.innerHTML = "No post(s) to see.";
        return;
    }

    filterSelection.forEach(btn => { // Filter
        btn.addEventListener("change", () => { // Check each checkbox
            if (btn.checked) { // Push the value of checked boxes in the filterArray
                filterArray.push(btn.value);
            } else {
                let index = filterArray.indexOf(btn.value); // Remove when unchecked
                filterArray.splice(index, 1);
            }

            if (filterArray.length === 0) { // If the filterArray is empty, there are no filters then render all posts
                renderPosts(data);
                return;
            }
            const filteredPosts = data.filter(post => filterArray.includes(post.category));
            
            if (filteredPosts.length === 0) {
                postsDiv.innerHTML = "No post(s) to see.";
                return;
            } else {
                renderPosts(filteredPosts);
            }
        });
        renderPosts(data);
    });
});

function renderPosts(posts) {
    postsDiv.innerHTML = "";

    /** Set non-integer ID to order posts based on php SELECT */
    /** Randomize posts */

    const groupedPosts = posts.reduce((groupedPostsArray, post) => { // Initialize array for grouping, iterate through posts by element post
        if (!groupedPostsArray[post.postId]) { // If post ID is not in new array
            groupedPostsArray[post.postId] = []; // Create a new array for post with ID 
        }
        groupedPostsArray[post.postId].push(post); // Push post info to the array
        return groupedPostsArray;
    }, {}); // Curly braces here is the groupedPostsArray

    Object.entries(groupedPosts).forEach(([id, group]) => { // Iterate through groupedPostsArray per ID/go into each group of posts/images per ID
        const innerPostDiv = document.createElement("div");
        const postId = group[0].postId;
        innerPostDiv.id = postId;
        innerPostDiv.classList.add("post");

        const postUsername = document.createElement("h4"); // Set username
        postUsername.textContent = group[0].username;
        innerPostDiv.appendChild(postUsername);

        const caption = document.createElement("p"); // Create paragraph element for caption
        caption.textContent = group[0].content || ""; // Per group, use the first group.content [since all content per group is the same]

        group.forEach(post => { // Iterate through each group
            if (post.image != null) { // If image exists
                const img = document.createElement("img"); // Create img element
                img.src = post.image; // Use path as source
                innerPostDiv.appendChild(img);
            }
        });
        
        if (caption.textContent != "") { // Caption is not blank
            innerPostDiv.appendChild(caption);
        }

        const commentBtn = document.createElement("button"); // Create comment button
        commentBtn.textContent = "Comment";

        commentBtn.addEventListener("click", () => { // When button is clicked
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
            commenterField.value = document.getElementById("imageUploadUserId").value;

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
        if (data === null) {
            commentsDiv.innerHTML = "No comment(s) yet.";
            return;
        }

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

            commentsDiv.appendChild(commentContainer)
        });
    });
}