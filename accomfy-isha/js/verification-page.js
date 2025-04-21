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

    // Handle verification option selection
    const options = document.querySelectorAll(".option");
    const continueBtn = document.querySelector(".continue-btn");
    
    options.forEach(option => {
        option.addEventListener("click", function() {
            options.forEach(opt => opt.classList.remove("selected"));
            this.classList.add("selected");
            continueBtn.disabled = false;
        });
    });

    // Handle continue button click
    continueBtn.addEventListener("click", function() {
        const selectedOption = document.querySelector(".option.selected");
        if (selectedOption) {
            const method = selectedOption.querySelector("p").textContent;
            alert(`Proceeding with ${method}...`);
        }
    });

    // Initially disable the continue button
    continueBtn.disabled = true;

    // Handle info and skip link clicks
    const infoLink = document.querySelector(".info-link");
    const skipLink = document.querySelector(".skip-link");

    infoLink.addEventListener("click", function(e) {
        e.preventDefault();
        alert("Information about identity verification...");
    });

    skipLink.addEventListener("click", function(e) {
        e.preventDefault();
        alert("Skipping verification process...");
    });
});