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

    // Handle verification button click
    const verifyBtn = document.querySelector(".verify-btn");
    verifyBtn.addEventListener("click", function() {
        alert("Proceeding with verification method selection...");
    });

    // Handle language change click
    const changeLangLink = document.querySelector(".change-lang");
    changeLangLink.addEventListener("click", function(e) {
        e.preventDefault();
        alert("Language change functionality to be implemented.");
    });

    // Handle skip link click
    const skipLink = document.querySelector(".skip-link");
    skipLink.addEventListener("click", function(e) {
        e.preventDefault();
        alert("Information about identity verification...");
    });
});