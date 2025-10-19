// Display user posts - Force clear and rebuild approach
(function() {
    'use strict';
    
    console.log("=== POST DISPLAY SCRIPT LOADED ===");
    
    // Wait for DOM to be fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPosts);
    } else {
        initPosts();
    }
    
    function initPosts() {
        // Wait a bit for get_user_info.js to populate the userId
        setTimeout(loadAndRenderPosts, 800);
    }
    
    function loadAndRenderPosts() {
        const postsContainer = document.querySelector(".posts");
        
        if (!postsContainer) {
            console.error("ERROR: .posts container not found!");
            return;
        }
        
        console.log("Posts container found, clearing it...");
        
        // FORCE CLEAR - Remove all children
        while (postsContainer.firstChild) {
            postsContainer.removeChild(postsContainer.firstChild);
        }
        
        // Add loading indicator
        const loading = document.createElement('div');
        loading.className = 'empty-state';
        loading.innerHTML = '<p>Loading posts...</p>';
        postsContainer.appendChild(loading);
        
        // Get user ID
        const userIdInput = document.getElementById("userId");
        if (!userIdInput || !userIdInput.value) {
            console.error("ERROR: userId not found or empty");
            postsContainer.innerHTML = '<div class="empty-state"><p>⚠️ User session not found</p></div>';
            return;
        }
        
        const currentUserId = userIdInput.value;
        console.log("Current userId:", currentUserId);
        
        // Fetch data
        Promise.all([
            fetch("user_info.json").then(res => {
                if (!res.ok) throw new Error('Failed to load user_info.json');
                return res.json();
            }),
            fetch("posts.json").then(res => {
                if (!res.ok) throw new Error('Failed to load posts.json');
                return res.json();
            })
        ])
        .then(([userData, postsData]) => {
            console.log("✓ Data loaded successfully");
            console.log("Users:", userData);
            console.log("Posts:", postsData);
            
            // Build user lookup
            const usersMap = {};
            userData.forEach(user => {
                usersMap[user.userId] = {
                    username: user.username,
                    email: user.userEmail || user.email || '',
                    profileImage: user.profileImage || ''
                };
            });
            
            // Filter posts for current user
            const userPosts = postsData.filter(post => post.userId === currentUserId);
            console.log(`Found ${userPosts.length} posts for user ${currentUserId}`);
            
            if (userPosts.length === 0) {
                postsContainer.innerHTML = '<div class="empty-state"><p>📝 No posts yet</p></div>';
                return;
            }
            
            // Group by postId
            const postGroups = {};
            userPosts.forEach(post => {
                if (!postGroups[post.postId]) {
                    postGroups[post.postId] = [];
                }
                postGroups[post.postId].push(post);
            });
            
            // Clear loading and render posts
            postsContainer.innerHTML = '';
            
            // Sort post IDs (newest first)
            const postIds = Object.keys(postGroups).sort((a, b) => parseInt(b) - parseInt(a));
            
            console.log(`Rendering ${postIds.length} post groups...`);
            
            postIds.forEach((postId, index) => {
                const posts = postGroups[postId];
                const post = posts[0]; // Main post data
                
                console.log(`Rendering post ${postId}`);
                
                const card = createPostCard(post, posts, usersMap);
                postsContainer.appendChild(card);
            });
            
            console.log("✓ All posts rendered successfully!");
        })
        .catch(error => {
            console.error("ERROR loading posts:", error);
            postsContainer.innerHTML = `<div class="empty-state"><p>⚠️ Error: ${error.message}</p></div>`;
        });
    }
    
    function createPostCard(post, allPostImages, usersMap) {
        // Main card
        const card = document.createElement('div');
        card.className = 'post-card';
        card.setAttribute('data-post-id', post.postId);
        
        // User info
        const userData = usersMap[post.userId] || { username: 'Unknown User', email: '' };
        
        // Header
        const header = document.createElement('div');
        header.className = 'post-header';
        
        const userInfo = document.createElement('div');
        userInfo.className = 'post-user-info';
        
        // Avatar
        const avatar = document.createElement('div');
        avatar.className = 'post-avatar';
        
        if (userData.profileImage) {
            const img = document.createElement('img');
            img.src = userData.profileImage;
            img.alt = userData.username;
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;border-radius:50%';
            avatar.appendChild(img);
        } else {
            avatar.textContent = userData.username.charAt(0).toUpperCase();
        }
        
        // User details
        const details = document.createElement('div');
        details.className = 'post-user-details';
        
        const username = document.createElement('h4');
        username.textContent = userData.username;
        
        const meta = document.createElement('p');
        meta.innerHTML = 'Just now';
        if (post.category) {
            meta.innerHTML += ` • <span class="post-category">${post.category}</span>`;
        }
        
        details.appendChild(username);
        details.appendChild(meta);
        
        userInfo.appendChild(avatar);
        userInfo.appendChild(details);
        
        const menu = document.createElement('div');
        menu.className = 'post-menu';
        menu.innerHTML = '⋯';
        
        header.appendChild(userInfo);
        header.appendChild(menu);
        card.appendChild(header);
        
        // Content/Caption
        if (post.content && post.content.trim()) {
            const content = document.createElement('p');
            content.className = 'post-text';
            content.textContent = post.content;
            card.appendChild(content);
        }
        
        // Images
        const images = allPostImages.filter(p => p.image && p.image.trim());
        if (images.length > 0) {
            const imgContainer = document.createElement('div');
            imgContainer.className = 'post-image-container';
            
            const img = document.createElement('img');
            img.className = 'post-image';
            img.src = images[0].image;
            img.alt = 'Post image';
            img.onerror = function() {
                this.style.display = 'none';
                console.error('Failed to load:', this.src);
            };
            
            imgContainer.appendChild(img);
            
            // Multiple images badge
            if (images.length > 1) {
                const badge = document.createElement('div');
                badge.className = 'image-counter-badge';
                badge.textContent = `+${images.length - 1} more`;
                imgContainer.appendChild(badge);
            }
            
            card.appendChild(imgContainer);
        }
        
        // Stats
        const stats = document.createElement('div');
        stats.className = 'post-stats';
        stats.innerHTML = '<span>0 likes</span><span>0 comments</span>';
        card.appendChild(stats);
        
        // Actions
        const actions = document.createElement('div');
        actions.className = 'post-actions';
        
        ['Like', 'Comment', 'Share'].forEach(text => {
            const btn = document.createElement('button');
            btn.className = 'post-action-btn';
            btn.textContent = text;
            actions.appendChild(btn);
        });
        
        card.appendChild(actions);
        
        return card;
    }
})();