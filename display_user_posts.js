fetch("posts.json")
.then(res => res.json())
.then(data => {
    const userId = document.getElementById("userId").value;
    const filterPostsByUserId = data.filter(post => post.userId === userId);

    if (filterPostsByUserId.length === 0) postsDiv.innerHTML = "No post(s) yet."
    else renderPosts(filterPostsByUserId);
});

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
        const innerPostDiv = document.createElement("div");
        innerPostDiv.classList.add("post");

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

        postsDiv.appendChild(innerPostDiv);
    });
}