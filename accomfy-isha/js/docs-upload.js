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
    const uploadedFile = document.querySelector("#uploadedFile");
    const continueBtn = document.querySelector("#continueBtn");
    const removeFileBtn = document.querySelector("#removeFileBtn");

    uploadBox.addEventListener("click", function() {
        fileInput.click();
    });

    fileInput.addEventListener("change", function() {
        if (fileInput.files.length > 0) {
            uploadedFile.style.display = "flex";
            continueBtn.disabled = false;
        }
    });

    removeFileBtn.addEventListener("click", function() {
        fileInput.value = "";
        uploadedFile.style.display = "none";
        continueBtn.disabled = true;
    });

    // Handle continue button click
    continueBtn.addEventListener("click", function() {
        if (fileInput.files.length > 0) {
            alert("Documents uploaded. Proceeding with verification...");
        }
    });

    // Initially disable the continue button
    continueBtn.disabled = true;

    // Handle info link click
    const infoLink = document.querySelector(".info-link");
    infoLink.addEventListener("click", function(e) {
        e.preventDefault();
        alert("Information about identity verification...");
    });
});