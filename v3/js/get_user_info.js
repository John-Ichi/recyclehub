fetch("user_info.json?nocache=" + new Date().getTime())
.then(res => res.json())
.then(data => {
    if (data === null) return;
    
    const userId = data[0].userId;
    
    const imageUploadUserId = document.getElementById("imageUploadUserId");
    if (imageUploadUserId) {
        imageUploadUserId.value = userId;
    }

    if (document.getElementById("username")) {
        document.getElementById("username").innerHTML = data[0].username;
    }

    if (document.getElementById("userId")) {
        document.getElementById("userId").value = userId;
    }

    if (document.getElementById("userEmail")) {
        document.getElementById("userEmail").innerHTML = data[0].userEmail;
    }

    let usersFollowed = [];

    data.forEach(user => {
        usersFollowed.push(user.followee);
    });

    window.usersFollowed = data.map(u => u.followee);
});