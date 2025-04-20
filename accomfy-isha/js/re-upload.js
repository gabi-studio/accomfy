document.addEventListener("DOMContentLoaded", function() {
    // Handle navigation link clicks
    const navLinks = document.querySelectorAll(".nav-link");
    navLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            navLinks.forEach(l => l.classList.remove("active"));
            this.classList.add("active");
        });
    });

    // Handle file upload
    const uploadBox = document.querySelector(".upload-box");
    const fileInput = document.querySelector("#fileInput");
    const uploadedFilesContainer = document.querySelector("#uploadedFiles");
    const continueBtn = document.querySelector("#continueBtn");

    uploadBox.addEventListener("click", function() {
        fileInput.click();
    });

    fileInput.addEventListener("change", function() {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const fileSize = (file.size / (1024 * 1024)).toFixed(1); // Convert to MB
            const fileElement = document.createElement("div");
            fileElement.classList.add("uploaded-file");
            fileElement.innerHTML = `
                <div class="file-info">
                    <img src="https://img.icons8.com/ios-filled/24/000000/pdf.png" alt="PDF Icon">
                    <span>${file.name}</span>
                    <span class="file-size">${fileSize} mb</span>
                </div>
                <button class="remove-file">
                    <img src="https://img.icons8.com/ios-filled/24/ff0000/trash.png" alt="Trash Icon">
                </button>
            `;
            uploadedFilesContainer.appendChild(fileElement);
            continueBtn.disabled = false;

            // Add event listener to remove button
            fileElement.querySelector(".remove-file").addEventListener("click", function() {
                uploadedFilesContainer.removeChild(fileElement);
                fileInput.value = "";
                if (uploadedFilesContainer.children.length === 0) {
                    continueBtn.disabled = true;
                }
            });
        }
    });

    // Handle continue button click
    continueBtn.addEventListener("click", function() {
        if (uploadedFilesContainer.children.length > 0) {
            alert("Documents uploaded. Proceeding with verification...");
        }
    });
});