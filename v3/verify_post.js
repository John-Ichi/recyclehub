const postModal = document.getElementById("postModal");
const postForm = document.getElementById("postForm");
const imageUpload = document.getElementById("imageUpload");
const previewContainer = document.getElementById("previewContainer");
const closeBtn = document.getElementsByClassName("close")[0];


closeBtn.addEventListener("click", () => { 

    postForm.reset();
    previewContainer.innerHTML = "";
    postModal.style.display = "none";
});

imageUpload.addEventListener("change", () => {
    previewContainer.innerHTML = "";

    const MAX_FILE_SIZE = 10 * 1024 * 1024;
    const files = imageUpload.files;
    const numOfFiles = imageUpload.files.length;

    if (numOfFiles > 10) { 
        alert("The maximum number of uploads is 10.");
        imageUpload.value = "";
        previewContainer.innerHTML = "";
        return;
    } else {
        for (const file of files) {
            if (file.size > MAX_FILE_SIZE) {
                alert("The maximum file size allowed is 10 MB."); 
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