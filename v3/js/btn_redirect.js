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
if (document.getElementById("searchButton")) {
    document.getElementById("searchButton").addEventListener("click", () => {
        const searchQuery = document.getElementById("searchBar").value;
        if (searchQuery.trim()) {
            window.location.href = `search.php?searchUser=${encodeURIComponent(searchQuery)}`;
        } else {
            window.location.href = "search.php";
        }
    });
    
    document.getElementById("searchBar").addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            document.getElementById("searchButton").click();
        }
    });
}