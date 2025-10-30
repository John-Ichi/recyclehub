fetch("user_info.json")
.then(res => res.json())
.then(data => {
    document.getElementById("username").innerHTML = data[0].username;
    document.getElementById("userId").value = data[0].userId;
    document.getElementById("userEmail").innerHTML = data[0].userEmail;

    let usersFollowed = [];

    data.forEach(user => {
        usersFollowed.push(user.followee);
    });

    window.usersFollowed = data.map(u => u.followee);
});