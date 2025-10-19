let filterArray = [];
let usersData = {}; // Store user data

const filterSelection = document.querySelectorAll(".filterPosts");

// Fetch user data first
fetch("user_info.json")
.then(res => res.json())
.then(userData => {
    userData.forEach(user => {
        usersData[user.userId] = user;
    });
    
    // Then fetch posts
    return fetch("posts.json");
})
.then(res => res.json())
.then(data => {
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
            renderPosts(filteredPosts);
        });
        
        renderPosts(data);
    });
})
.catch(err => console.error('Error fetching data:', err));

const postsDiv = document.querySelector(".posts");

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
        // Create main post card container
        const postCard = document.createElement("div");
        postCard.classList.add("post-card");

        // Create post header
        const postHeader = document.createElement("div");
        postHeader.classList.add("post-header");
        
        const postUserInfo = document.createElement("div");
        postUserInfo.classList.add("post-user-info");
        
        const postAvatar = document.createElement("div");
        postAvatar.classList.add("post-avatar");
        
        const postUserDetails = document.createElement("div");
        postUserDetails.classList.add("post-user-details");
        
        // Get username from usersData
        const userId = group[0].userId;
        const username = usersData[userId]?.username || "User";
        const userAvatar = usersData[userId]?.profileImage || "";
        
        if (userAvatar) {
            postAvatar.style.backgroundImage = `url(${userAvatar})`;
            postAvatar.style.backgroundSize = "cover";
        }
        
        const userName = document.createElement("h4");
        userName.textContent = username;
        
        const postTime = document.createElement("p");
        postTime.textContent = "Just now";
        
        postUserDetails.appendChild(userName);
        postUserDetails.appendChild(postTime);
        postUserInfo.appendChild(postAvatar);
        postUserInfo.appendChild(postUserDetails);
        postHeader.appendChild(postUserInfo);
        
        // Add menu button
        const postMenu = document.createElement("div");
        postMenu.classList.add("post-menu");
        postMenu.textContent = "...";
        postHeader.appendChild(postMenu);
        
        postCard.appendChild(postHeader);

        // Create post caption
        const captionText = group[0].content || "";
        if (captionText !== "") {
            const caption = document.createElement("p");
            caption.classList.add("post-text");
            caption.textContent = captionText;
            postCard.appendChild(caption);
        }

        // Create post image container
        const imageContainer = document.createElement("div");
        imageContainer.classList.add("post-image-container");

        // Count images first to determine layout
        const imageCount = group.filter(post => post.image).length;
        
        // Add multi-image class if more than one image
        if (imageCount > 1) {
            imageContainer.classList.add("multi-image");
            
            // DEBUG - Check in console
            console.log("Post", id, "has", imageCount, "images - adding multi-image class");
            
            // Optional: Add two-images class for side-by-side layout
            if (imageCount === 2) {
                imageContainer.classList.add("two-images");
                console.log("Adding two-images class for side-by-side layout");
            }
        }

        group.forEach(post => {
            if (post.image) {
                const img = document.createElement("img");
                img.classList.add("post-image");
                img.src = post.image;
                console.log("Adding image:", post.image);
                imageContainer.appendChild(img);
            }
        });

        if (imageContainer.children.length > 0) {
            postCard.appendChild(imageContainer);
        }

        // Create post stats
        const postStats = document.createElement("div");
        postStats.classList.add("post-stats");
        postStats.innerHTML = '<span>0 likes</span><span>0 comments</span>';
        postCard.appendChild(postStats);

        // Create post actions
        const postActions = document.createElement("div");
        postActions.classList.add("post-actions");
        postActions.innerHTML = '<button class="post-action-btn">Like</button><button class="post-action-btn">Comment</button><button class="post-action-btn">Share</button>';
        postCard.appendChild(postActions);

        postsDiv.appendChild(postCard);
    });
}