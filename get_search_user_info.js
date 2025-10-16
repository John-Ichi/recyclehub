const urlParams = new URLSearchParams(window.location.search);
let urlParamsVal = urlParams.get("user");

fetch("users.json")
.then(res => res.json())
.then(data => {
    const userData = data.filter(user => user.userId === urlParamsVal);

    document.getElementById("username").innerHTML = userData[0].username;
    document.getElementById("userId").value = userData[0].userId;
    document.getElementById("userEmail").innerHTML = userData[0].userEmail;
});