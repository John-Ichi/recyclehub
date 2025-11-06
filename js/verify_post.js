const postModal = document.getElementById("postModal");
const postBtn = document.getElementById("createPost");
const postForm = document.getElementById("postForm");
const imageUpload = document.getElementById("imageUpload");
const previewContainer = document.getElementById("previewContainer");
const closePostModalBtn = postModal.querySelector(".close");

postBtn.addEventListener("click", () => {
    postModal.style.display = "block";
});

closePostModalBtn.addEventListener("click", () => { // Close post modal [note: add a confirmation for discarding post]
    if (confirm("Are you sure you want to terminate posting?")) {
        postForm.reset();
        previewContainer.innerHTML = "";
        postModal.style.display = "none";
    }
});

// Image preview for selected images
imageUpload.addEventListener("change", () => {
    previewContainer.innerHTML = "";

    const MAX_FILE_SIZE = 10 * 1024 * 1024;
    const files = imageUpload.files;
    const numOfFiles = imageUpload.files.length;

    if (numOfFiles > 5) { // Limit number of posts client-side
        alert("The maximum number of uploads is 5.");
        imageUpload.value = "";
        previewContainer.innerHTML = "";
        return;
    } else {
        for (const file of files) {
            if (file.size > MAX_FILE_SIZE) {
                alert("The maximum file size allowed is 10 MB."); // Client-side file size check
                imageUpload.value = "";
                previewContainer.innerHTML = "";
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