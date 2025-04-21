document.addEventListener("DOMContentLoaded", function() {
    // Handle navigation link clicks
    const navLinks = document.querySelectorAll(".nav-link");
    navLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            alert(`Navigating to ${this.textContent}...`);
        });
    });

    // Handle register link click
    const registerLink = document.querySelector(".register-link a");
    registerLink.addEventListener("click", function(e) {
        e.preventDefault();
        alert("Navigating to registration page...");
    });

    // Handle List a Property button click
    const listPropertyBtn = document.querySelector(".btn.primary");
    listPropertyBtn.addEventListener("click", function() {
        alert("Navigating to List a Property...");
    });

    // Handle Log in button click
    const loginBtn = document.querySelector(".btn:not(.primary)");
    loginBtn.addEventListener("click", function() {
        alert("Navigating to Log in...");
    });

    // Handle back link click
    const backLink = document.querySelector(".back-link");
    backLink.addEventListener("click", function(e) {
        e.preventDefault();
        alert("Going back...");
    });

    // Handle sign-up form submission
    const signupBtn = document.querySelector(".signup-btn");
    signupBtn.addEventListener("click", function(e) {
        e.preventDefault();
        const name = document.querySelector("#name").value;
        const email = document.querySelector("#email").value;
        const password = document.querySelector("#password").value;

        if (name && email && password) {
            alert(`Signing up with Name: ${name}, Email: ${email}`);
        } else {
            alert("Please fill in all fields.");
        }
    });

    // Handle forgot password link
    const forgotPasswordLink = document.querySelector(".forgot-password");
    forgotPasswordLink.addEventListener("click", function(e) {
        e.preventDefault();
        alert("Navigating to Forgot Password...");
    });

    // Handle social login buttons
    const socialButtons = document.querySelectorAll(".social-btn");
    socialButtons.forEach(button => {
        button.addEventListener("click", function() {
            const provider = this.textContent.trim();
            alert(`Logging in with ${provider}...`);
        });
    });

    // Handle view properties button
    const viewPropertiesBtn = document.querySelector(".view-properties-btn");
    viewPropertiesBtn.addEventListener("click", function() {
        alert("Viewing properties...");
    });

    // Handle footer link clicks
    const footerLinks = document.querySelectorAll(".footer a");
    footerLinks.forEach(link => {
        link.addEventListener("click", function(e) {
            e.preventDefault();
            alert(`Navigating to ${this.textContent}...`);
        });
    });
});