<?php

include 'functions.php';

if (!isset($_SESSION['email']) && !isset($_SESSION['username']) && !isset($_SESSION['user'])) {
    header('Location: index.php');
}

getPosts();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecycleHub</title>
    
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0,);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .thumbnail {
            height: 10%;
            width: 10%;
        }

        img {
            height: 10%;
            width: 10%;
        }
    </style>

</head>
<body>
    <h1>News Feed</h1>
    <button id="createPost">Create Post</button>
    <button id="logOut">Log Out</button>

    <div class="posts"> <!-- Posts -->

    </div>

    <div id="postModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>What are your recycling ideas?</h2>
            <form id="postForm" action="functions.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="images[]" id="imageUpload" accept="image/png, image/jpeg" multiple required>
                    <div id="previewContainer">
                    </div>
                <br>
                <textarea name="text_content" rows="10" cols="100%" maxlength="250"></textarea>
                <button type="submit" name="create_post">Create Post</button>
            </form>
        </div>
    </div>
</body>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    btn = document.getElementById("logOut"); // Log out API call
    btn.addEventListener("click", () => {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (xhttp.responseText == "true") {
                    window.location.href = "index.php";
                }
            }
        };
        xhttp.open("GET", "session_unset.php", true);
        xhttp.send();
    });

    const postModal = document.getElementById("postModal");
    const postBtn = document.getElementById("createPost");
    const postForm = document.getElementById("postForm");
    const imageUpload = document.getElementById("imageUpload");
    const previewContainer = document.getElementById("previewContainer");
    const closeBtn = document.getElementsByClassName("close")[0];

    postBtn.addEventListener("click", () => {
        postModal.style.display = "block";
    });

    closeBtn.addEventListener("click", () => { // Close post modal [note: add a confirmation for discarding post]
        postForm.reset();
        previewContainer.innerHTML = "";
        postModal.style.display = "none";
    });

    // Image preview for selected images
    imageUpload.addEventListener("change", () => {
        previewContainer.innerHTML = "";

        const MAX_FILE_SIZE = 10 * 1024 * 1024;
        const files = imageUpload.files;
        const numOfFiles = imageUpload.files.length;

        if (numOfFiles > 10) { // Limit number of posts client-side
            alert("The maximum number of uploads is 10.");
            imageUpload.value = "";
            return;
        } else {
            for (const file of files) {
                if (file.size > MAX_FILE_SIZE) {
                    alert("The maximum file size allowed is 10 MB."); // Client-side file size check
                    imageUpload.value = ""; 
                    return;
                } else {
                    const reader = new FileReader();
                    reader.addEventListener("load", (e) => {
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("thumbnail");
                        previewContainer.appendChild(img);
                    });
                    reader.readAsDataURL(file);
                }
            }
        }
    });
</script>

<script src="home.js" defer></script>

</html>