const postsDiv = document.querySelector(".posts");

const filterSelection = document.querySelectorAll(".filterPosts");

let filterArray = [];

fetch("posts.json")
.then(res => res.json())
.then(data => {
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
            renderPosts(filteredPosts);
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
        
        if (caption.textContent != "") {
            innerPostDiv.appendChild(caption);
        }

        postsDiv.appendChild(innerPostDiv);
    });
}

    /**
     * Manual Implementation
    let postIds = [] // Array for storing post IDs

    posts.forEach(post => { // Push post ID to postIds[]
        postIds.push(post.postId);
    });

    const uniquePosts = [...new Set(postIds)]; Create a set with unique IDS only (clear duplicates)

    uniquePosts.forEach(ID => { // For each ID
        
        const innerPostDiv = document.createElement("div"); // Create a container
        const caption = document.createElement("p"); // Create a paragraph element for captions

        posts.forEach(post => { // Loop through all posts
            if (ID === post.postId) { // Check the for ID match
                caption.innerHTML = post.content; // Assign caption
                const image = document.createElement("img");
                image.setAttribute("src", post.image); // Add all images with corresponding ID to container
                innerPostDiv.appendChild(image);
            }
        });

        if (caption.innerHTML != "") { // Append caption if not empty
            innerPostDiv.appendChild(caption);
        }

        postsDiv.appendChild(innerPostDiv);
    });
    */