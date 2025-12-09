document.addEventListener('DOMContentLoaded', function() {
    const followButton = document.getElementById('followButton');
    const currentUserIdInput = document.getElementById('userId');
    const searchUserIdInput = document.getElementById('searchUserId');
    const currentUsernameEl = document.getElementById('username');
    const searchUsernameEl = document.getElementById('searchUsername');
    
    let lastSearchUserId = '';

    setTimeout(function() {
        initializeFollowButton();
    }, 500);
    
    setInterval(function() {
        const searchUserId = searchUserIdInput ? searchUserIdInput.value : '';
        if (searchUserId && searchUserId !== lastSearchUserId) {
            lastSearchUserId = searchUserId;
            console.log('User search changed, re-initializing follow button');
            initializeFollowButton();
        }
    }, 1000);
    
    function initializeFollowButton() {
        const currentUserId = currentUserIdInput ? currentUserIdInput.value.trim() : '';
        const searchUserId = searchUserIdInput ? searchUserIdInput.value.trim() : '';

        const currentUsername = currentUsernameEl ? currentUsernameEl.textContent.trim() : '';
        const searchUsername = searchUsernameEl ? searchUsernameEl.textContent.trim() : '';

        const currentPage = window.location.pathname.split('/').pop();
        
        console.log('=== Follow Button Debug ===');
        console.log('Current Page:', currentPage);
        console.log('Current User ID:', currentUserId);
        console.log('Search User ID:', searchUserId);
        console.log('Current Username:', currentUsername);
        console.log('Search Username:', searchUsername);
        console.log('IDs equal?', currentUserId === searchUserId);
        console.log('Usernames equal?', currentUsername === searchUsername);
        console.log('=========================');

        if (currentPage === 'profile.php') {
            if (followButton) {
                followButton.style.display = 'none';
                console.log('Follow button HIDDEN - on profile.php (own profile)');
            }
            return;
        }

        const isSameUser = (currentUserId && searchUserId && currentUserId === searchUserId) ||
                          (currentUsername && searchUsername && currentUsername === searchUsername);
        
        if (!searchUserId && !searchUsername) {

            if (followButton) {
                followButton.style.display = 'none';
                console.log('Follow button HIDDEN - no search user');
            }
            return;
        }
        
        if (isSameUser) {
            if (followButton) {
                followButton.style.display = 'none';
                console.log('Follow button HIDDEN - viewing own profile');
            }
            return;
        }

        if (followButton) {
            followButton.style.display = 'block';
            console.log('Follow button VISIBLE - viewing another user');

            checkFollowStatus();
        }
    }

    followButton.addEventListener('click', function() {
        if (followButton.classList.contains('following')) {
            unfollowUser();
        } else {
            followUser();
        }
    });

    function checkFollowStatus() {
        const currentUserId = currentUserIdInput.value;
        const searchUserId = searchUserIdInput.value;
        
        console.log('Checking follow status...');
        console.log('Follower ID:', currentUserId, 'Followee ID:', searchUserId);
        
        fetch('check_follow_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `follower_id=${currentUserId}&followee_id=${searchUserId}`
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); 
        })
        .then(text => {
            console.log('Raw response:', text);
            try {
                const data = JSON.parse(text);
                console.log('Parsed data:', data);
                
                if (data.isFollowing) {
                    followButton.classList.add('following');
                    followButton.textContent = 'Following';
                    console.log('User IS following');
                } else {
                    followButton.classList.remove('following');
                    followButton.textContent = 'Follow';
                    console.log('User is NOT following');
                }
            } catch (e) {
                console.error('JSON parse error:', e);
                console.error('Received text:', text);
            }
        })
        .catch(error => {
            console.error('Error checking follow status:', error);
        });
    }

    function followUser() {
        const currentUserId = currentUserIdInput.value;
        const searchUserId = searchUserIdInput.value;

        if (currentUserId === searchUserId) {
            alert('You cannot follow yourself!');
            return;
        }
        
        fetch('follow.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `follow_user=1&follower_id=${currentUserId}&followee_id=${searchUserId}`
        })
        .then(response => response.text())
        .then(data => {
            console.log('Follow response:', data);
            
            if (data === 'error_self_follow') {
                alert('You cannot follow yourself!');
            } else if (data === 'already_following') {
                followButton.classList.add('following');
                followButton.textContent = 'Following';
            } else if (data === 'error') {
                alert('Failed to follow user. Please try again.');
            } else if (data) {
                followButton.classList.add('following');
                followButton.textContent = 'Following';
                console.log('Successfully followed user');
            }
        })
        .catch(error => {
            console.error('Error following user:', error);
            alert('Failed to follow user. Please try again.');
        });
    }

    function unfollowUser() {
        const currentUserId = currentUserIdInput.value;
        const searchUserId = searchUserIdInput.value;
        
        fetch('follow.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `unfollow_user=1&follower_id=${currentUserId}&followee_id=${searchUserId}`
        })
        .then(response => response.text())
        .then(data => {
            console.log('Unfollow response:', data);
            
            if (data === 'error') {
                alert('Failed to unfollow user. Please try again.');
            } else if (data) {
                followButton.classList.remove('following');
                followButton.textContent = 'Follow';
                console.log('Successfully unfollowed user');
            }
        })
        .catch(error => {
            console.error('Error unfollowing user:', error);
            alert('Failed to unfollow user. Please try again.');
        });
    }

    followButton.addEventListener('mouseenter', function() {
        if (followButton.classList.contains('following')) {
            followButton.textContent = 'Unfollow';
        }
    });

    followButton.addEventListener('mouseleave', function() {
        if (followButton.classList.contains('following')) {
            followButton.textContent = 'Following';
        }
    });
});