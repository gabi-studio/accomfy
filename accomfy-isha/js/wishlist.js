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

    // Handle property card clicks
    const propertyCards = document.querySelectorAll(".property-card");
    propertyCards.forEach(card => {
        card.addEventListener("click", function() {
            const propertyName = this.querySelector("h3").textContent;
            alert(`Navigating to ${propertyName} details...`);
        });
    });

    // Handle pagination clicks
    const paginationButtons = document.querySelectorAll(".pagination button");
    paginationButtons.forEach(button => {
        button.addEventListener("click", function() {
            paginationButtons.forEach(btn => btn.classList.remove("active"));
            if (!this.classList.contains("prev") && !this.classList.contains("next")) {
                this.classList.add("active");
            }
        });
    });

    // Handle footer link clicks
    const footerLinks = document.querySelectorAll(".footer a");
    footerLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            alert(`Navigating to ${this.textContent}`);
        });
    });
});