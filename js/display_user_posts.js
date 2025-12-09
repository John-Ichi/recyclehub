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
    console.log("Fetched posts data:", data);
    
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
    
    console.log("Filtering by userId:", userId);
    const filterPostsByUserId = data.filter(post => post.userId === userId);
    console.log("Filtered posts:", filterPostsByUserId);

    if (filterPostsByUserId.length === 0) {
        postsDiv.innerHTML = "No post(s) yet."
        return;
    }
    
    renderPosts(filterPostsByUserId);
    
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
    });
})
.catch(error => {
    console.error("Error fetching posts:", error);
    postsDiv.innerHTML = "Error loading posts.";
});

function renderPosts(posts) {
    console.log("Rendering posts:", posts);
    postsDiv.innerHTML = "";

    const groupedPosts = posts.reduce((groupedPostsArray, post) => {
        if (!groupedPostsArray[post.postId]) {
            groupedPostsArray[post.postId] = [];
        }
        groupedPostsArray[post.postId].push(post);
        return groupedPostsArray;
    }, {});

    console.log("Grouped posts:", groupedPosts);

    Object.entries(groupedPosts).forEach(([id, group]) => {
        console.log("Rendering post group:", id, group);
        
        const firstPost = group[0];
        
        const postDiv = document.createElement("div");
        postDiv.classList.add("post");
        postDiv.id = firstPost.postId;

        const postHeader = document.createElement("div");
        postHeader.classList.add("post-header");

        const postUserInfo = document.createElement("div");
        postUserInfo.classList.add("post-user-info");

        const postAvatar = document.createElement("div");
        postAvatar.classList.add("post-avatar");
        postAvatar.textContent = firstPost.username ? firstPost.username.charAt(0).toUpperCase() : "?";

        const postUserDetails = document.createElement("div");
        postUserDetails.classList.add("post-user-details");

        const usernameH4 = document.createElement("h4");
        usernameH4.textContent = firstPost.username;

        const userMetaP = document.createElement("p");
        
        const categoryBadge = document.createElement("span");
        categoryBadge.classList.add("post-category");
        categoryBadge.textContent = firstPost.category || "Other";
        
        userMetaP.appendChild(categoryBadge);

        postUserDetails.appendChild(usernameH4);
        postUserDetails.appendChild(userMetaP);

        postUserInfo.appendChild(postAvatar);
        postUserInfo.appendChild(postUserDetails);

        const postMenu = document.createElement("div");
        postMenu.classList.add("post-menu");
        /*postMenu.textContent = "⋯";*/

        postHeader.appendChild(postUserInfo);
        postHeader.appendChild(postMenu);

        postDiv.appendChild(postHeader);

        if (firstPost.content && firstPost.content !== "") {
            console.log("Adding caption:", firstPost.content);
            const postText = document.createElement("div");
            postText.classList.add("post-text");
            postText.textContent = firstPost.content;
            postDiv.appendChild(postText);
        }


        const images = group.filter(post => post.image && post.image !== null && post.image !== "");
        
        if (images.length > 0) {
            if (images.length === 1) {

                const postImageContainer = document.createElement("div");
                postImageContainer.classList.add("post-image-container");
                
                const img = document.createElement("img");
                img.classList.add("post-image");
                img.src = images[0].image;
                img.alt = "Post image";
                
                postImageContainer.appendChild(img);
                postDiv.appendChild(postImageContainer);
            } else if (images.length === 2) {
                const postImageContainer = document.createElement("div");
                postImageContainer.classList.add("post-image-container", "multi-image", "two-images");
                
                images.forEach(post => {
                    const img = document.createElement("img");
                    img.classList.add("post-image");
                    img.src = post.image;
                    img.alt = "Post image";
                    postImageContainer.appendChild(img);
                });
                
                postDiv.appendChild(postImageContainer);
            } else {
                const postImageContainer = document.createElement("div");
                postImageContainer.classList.add("post-image-container", "multi-image");
                
                images.forEach(post => {
                    const img = document.createElement("img");
                    img.classList.add("post-image");
                    img.src = post.image;
                    img.alt = "Post image";
                    postImageContainer.appendChild(img);
                });
                
                postDiv.appendChild(postImageContainer);
            }
        }
        const postActions = document.createElement("div");
        postActions.classList.add("post-actions");

        const commentBtn = document.createElement("button");
        commentBtn.classList.add("post-action-btn");
        commentBtn.textContent = "Comment";
        commentBtn.addEventListener("click", () => {
            openCommentModal(firstPost.postId);
        });

        postActions.appendChild(commentBtn);
        postDiv.appendChild(postActions);

        postsDiv.appendChild(postDiv);
    });
}

function openCommentModal(postId) {
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
    commentField.placeholder = "Write a comment...";

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
    })
    .catch(error => {
        console.error("Error fetching comments:", error);
        commentsDiv.innerHTML = "Error loading comments.";
    });
}