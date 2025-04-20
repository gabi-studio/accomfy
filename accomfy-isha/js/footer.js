document.addEventListener("DOMContentLoaded", function() {
    const footerLinks = document.querySelectorAll(".footer a");
    
    footerLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            alert(`Navigating to ${this.textContent}`);
        });
    });
});