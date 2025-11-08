if (document.getElementById("returnToHome")) {
    document.getElementById("returnToHome").addEventListener("click", () => {
        window.location.href = "home.php";
    });
}

if (document.getElementById("goToProfile")) {
    document.getElementById("goToProfile").addEventListener("click", () => {
        window.location.href = "profile.php";
    });
}

if (document.getElementById("goToSearch")) {
    document.getElementById("goToSearch").addEventListener("click", () => {
        window.location.href = "search.php";
    });
}