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

    // Handle dashboard card clicks
    const dashboardCards = document.querySelectorAll(".dashboard-card");
    dashboardCards.forEach(card => {
        card.addEventListener("click", function() {
            const sectionTitle = this.querySelector("h3").textContent;
            alert(`Navigating to ${sectionTitle} section...`);
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