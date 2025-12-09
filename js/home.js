if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

document.addEventListener('DOMContentLoaded', function() {
    const searchBar = document.getElementById("searchBar");
    const searchButton = document.getElementById("searchButton");
    const filterSelection = document.querySelectorAll(".filterPosts");
    const postsDiv = document.querySelector(".posts");
    const commentModal = document.getElementById("commentModal");
    const closeCommentModalBtn = commentModal?.querySelector(".close-comment");
    const commentsDiv = document.getElementById("comments");
    const postCommentsDiv = document.getElementById("postComments");

    const postModal = document.getElementById("postModal");
    const createPostCard = document.getElementById("createPostCard");
    const cancelPostBtn = document.getElementById("cancelPostBtn");
    const imageUpload = document.getElementById("imageUpload");
    const previewContainer = document.getElementById("previewContainer");
    const postForm = document.getElementById("postForm");
    if (!searchBar || !searchButton || !postsDiv || !commentModal) {
        console.error("Required elements not found");
        return;
    }

    if (createPostCard && postModal) {
        createPostCard.addEventListener("click", () => {
            postModal.style.display = "block";
        });
    }

    if (cancelPostBtn && postModal) {
        cancelPostBtn.addEventListener("click", () => {
            postModal.style.display = "none";
            if (postForm) postForm.reset();
            if (previewContainer) previewContainer.innerHTML = "";
        });
    }

    if (imageUpload && previewContainer) {
        imageUpload.addEventListener("change", (e) => {
            previewContainer.innerHTML = "";
            const files = e.target.files;
            
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        const img = document.createElement("img");
                        img.src = event.target.result;
                        img.alt = "Preview " + (i + 1);
                        previewContainer.appendChild(img);
                    };
                    
                    reader.readAsDataURL(file);
                }
            }
        });
    }

    searchBar.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            
            const searchInput = searchBar.value.trim();

            if (searchInput !== "") {
                const urlParameters = "searchUser=" + searchInput;
                window.open(`search.php?${urlParameters}`);
            }
        }
    });

    searchButton.addEventListener("click", () => {
        const searchInput = searchBar.value.trim();

        if (searchInput !== "") {
            const urlParameters = "searchUser=" + searchInput;
            window.open("search.php?" + urlParameters);
        } else {
            window.open("search.php");
        }
    });

    if (closeCommentModalBtn) {
        closeCommentModalBtn.addEventListener("click", () => {
            commentModal.style.display = "none";
            if (commentsDiv) commentsDiv.innerHTML = "";
            if (postCommentsDiv) postCommentsDiv.innerHTML = "";
        });
    }

    window.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            if (commentModal && commentModal.style.display === "block") {
                commentModal.style.display = "none";
                if (commentsDiv) commentsDiv.innerHTML = "";
                if (postCommentsDiv) postCommentsDiv.innerHTML = "";
            }

            if (postModal && postModal.style.display === "block") {
                postModal.style.display = "none";
                if (postForm) postForm.reset();
                if (previewContainer) previewContainer.innerHTML = "";
            }
        }
    });

    window.addEventListener("click", (e) => {
        if (e.target === postModal) {
            postModal.style.display = "none";
            if (postForm) postForm.reset();
            if (previewContainer) previewContainer.innerHTML = "";
        }
        if (e.target === commentModal) {
            commentModal.style.display = "none";
            if (commentsDiv) commentsDiv.innerHTML = "";
            if (postCommentsDiv) postCommentsDiv.innerHTML = "";
        }
    });

    let filterArray = [];

    fetch("posts.json?nocache=" + new Date().getTime())
    .then(res => res.json())
    .then(data => {
        if (data === null || data.length === 0) {
            postsDiv.innerHTML = "<p style='text-align: center; padding: 40px; color: #65676b;'>No post(s) to see.</p>";
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
                    renderPosts(data);
                    return;
                }

                const filteredPosts = data.filter(post => filterArray.includes(post.category));
                if (filteredPosts.length === 0) {
                    postsDiv.innerHTML = "<p style='text-align: center; padding: 40px; color: #65676b;'>No post(s) to see.</p>";
                    return;
                } else {
                    renderPosts(filteredPosts);
                }
            });
        });
        renderPosts(data);
    })
    .catch(error => {
        console.error("Error loading posts:", error);
        postsDiv.innerHTML = "<p style='text-align: center; padding: 40px; color: #e85d5d;'>Error loading posts.</p>";
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
            // Create post card container
            const postCard = document.createElement("div");
            postCard.classList.add("post-card");
            postCard.id = group[0].postId;

            // Create post header
            const postHeader = document.createElement("div");
            postHeader.classList.add("post-header");

            const postUserInfo = document.createElement("div");
            postUserInfo.classList.add("post-user-info");

            // Avatar
            const postAvatar = document.createElement("div");
            postAvatar.classList.add("post-avatar");

            // User details
            const postUserDetails = document.createElement("div");
            postUserDetails.classList.add("post-user-details");

            const postUsername = document.createElement("h4");
            postUsername.textContent = group[0].username;

            const postMeta = document.createElement("p");
            const postCategory = document.createElement("span");
            postCategory.classList.add("post-category");
            postCategory.textContent = group[0].category || "General";
            postMeta.appendChild(postCategory);

            postUserDetails.appendChild(postUsername);
            postUserDetails.appendChild(postMeta);

            postUserInfo.appendChild(postAvatar);
            postUserInfo.appendChild(postUserDetails);
            postHeader.appendChild(postUserInfo);

            // Post menu (three dots)
            const postMenu = document.createElement("div");
            postMenu.classList.add("post-menu");
            //postMenu.textContent = "⋯";
            postHeader.appendChild(postMenu);

            postCard.appendChild(postHeader);

            // Create post content section
            const postContent = document.createElement("div");
            postContent.classList.add("post-content");

            // Add text content if exists
            if (group[0].content && group[0].content.trim() !== "") {
                const postText = document.createElement("div");
                postText.classList.add("post-text");
                postText.textContent = group[0].content;
                postContent.appendChild(postText);
            }

            // Handle images
            const images = group.filter(post => post.image != null);
            
            if (images.length > 0) {
                const postImageContainer = document.createElement("div");
                postImageContainer.classList.add("post-image-container");
                
                if (images.length === 2) {
                    postImageContainer.classList.add("multi-image", "two-images");
                } else if (images.length > 2) {
                    postImageContainer.classList.add("multi-image");
                }

                images.forEach(post => {
                    const img = document.createElement("img");
                    img.src = post.image;
                    img.classList.add("post-image");
                    img.alt = "Post image";
                    postImageContainer.appendChild(img);
                });

                postContent.appendChild(postImageContainer);
            }

            postCard.appendChild(postContent);

            // Create post actions
            const postActions = document.createElement("div");
            postActions.classList.add("post-actions");

            const commentBtn = document.createElement("button");
            commentBtn.classList.add("post-action-btn");
            commentBtn.textContent = "Comment";

            // Comment button functionality
            commentBtn.addEventListener("click", () => {
                commentModal.style.display = "block";
                if (postCommentsDiv) postCommentsDiv.innerHTML = "";

                const commentForm = document.createElement("form");
                commentForm.id = "commentForm";
                commentForm.action = "functions.php";
                commentForm.method = "POST";

                const postIdField = document.createElement("input");
                postIdField.type = "hidden";
                postIdField.name = "post_id";
                postIdField.value = group[0].postId;
                
                const commenterField = document.createElement("input");
                commenterField.type = "hidden";
                commenterField.name = "commenter";
                const userIdInput = document.getElementById("imageUploadUserId");
                commenterField.value = userIdInput ? userIdInput.value : "";

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
                if (postCommentsDiv) postCommentsDiv.appendChild(commentForm);
                renderComments();
            });


            postActions.appendChild(commentBtn);


            postCard.appendChild(postActions);
            postsDiv.appendChild(postCard);
        });
    }

    function renderComments() {
        const commentForm = document.getElementById("commentForm");
        if (!commentForm || !commentsDiv) return;
        
        const postId = commentForm.firstChild.value;

        fetch("comments.json?nocache=" + new Date().getTime())
        .then(res => res.json())
        .then(data => {
            if (data === null) {
                commentsDiv.innerHTML = "<p style='text-align: center; color: #65676b; padding: 20px;'>No comment(s) yet.</p>";
                return;
            }

            const postComments = data.filter(comment => comment.postId == postId);

            if (postComments.length === 0) {
                commentsDiv.innerHTML = "<p style='text-align: center; color: #65676b; padding: 20px;'>No comment(s) yet.</p>";
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
            console.error("Error loading comments:", error);
            commentsDiv.innerHTML = "<p style='text-align: center; color: #e85d5d; padding: 20px;'>Error loading comments.</p>";
        });
    }
});