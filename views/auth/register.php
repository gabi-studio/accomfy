<?php require_once '../../includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="/accomfy/public/css/styles.css">
    <link rel="stylesheet" href="/accomfy/public/css/register.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="/" class="brand">Accomfy</a>
        <ul class="nav-links">
            <li><a href="/accomfy/index.php">Home</a></li>
            <li><a href="/accomfy/views/auth/login.php">Login</a></li>
        </ul>
    </div>
</nav>

<div class="hero-container">
    <div class="hero-text">
        <h1>Create Your Accomfy Account</h1>
    </div>
</div>

<main class="form-container">
    <?php if (isset($_GET['error']) && $_GET['error'] === 'exists'): ?>
        <p style="color: red; text-align: center;">Email is already registered.</p>
    <?php elseif (isset($_GET['error']) && $_GET['error'] === 'failed'): ?>
        <p style="color: red; text-align: center;">Something went wrong. Please try again.</p>
    <?php endif; ?>

    <form class="registration-form" method="POST" action="/controllers/AuthController.php" enctype="multipart/form-data">
        <h2>Register</h2>

        <div class="form-group">
            <label for="first_name">First Name *</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name *</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number *</label>
            <input type="text" id="phone" name="phone" required>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="role">Register As *</label>
            <select id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="student">Student</option>
                <option value="landlord">Landlord</option>
            </select>
        </div>

        <div class="form-group">
            <label for="school">School</label>
            <input type="text" id="school" name="school">
        </div>

        <div class="form-group">
            <label for="program">Program</label>
            <input type="text" id="program" name="program">
        </div>

        <div class="form-group">
            <label for="about">About Me</label>
            <textarea id="about" name="about" rows="4"></textarea>
        </div>

        <h3>Living Preferences</h3>

        <div class="form-group">
            <label for="preferred_location">Preferred Location</label>
            <select id="preferred_location" name="preferred_location">
                <option value="">Any</option>
                <option value="Downtown">Downtown</option>
                <option value="Near Campus">Near Campus</option>
                <option value="Suburbs">Suburbs</option>
            </select>
        </div>

        <div class="form-group">
            <label for="preferred_furnishing">Preferred Furnishing</label>
            <select id="preferred_furnishing" name="preferred_furnishing">
                <option value="">Any</option>
                <option value="Furnished">Furnished</option>
                <option value="Semi-Furnished">Semi-Furnished</option>
                <option value="Unfurnished">Unfurnished</option>
            </select>
        </div>

        <div class="form-group">
            <label for="roommate_style">Roommate Style</label>
            <select id="roommate_style" name="roommate_style">
                <option value="">Any</option>
                <option value="Quiet">Quiet</option>
                <option value="Social">Social</option>
                <option value="Early Bird">Early Bird</option>
                <option value="Night Owl">Night Owl</option>
            </select>
        </div>

        <!-- upload documents -->

        <div class="form-group">
            <label for="document">Upload your ID for verification</label>
            <input type="file" id="document" name="document">
        </div>


            

        <button type="submit" name="register" class="btn">Register</button>
    </form>
</main>

<footer>
    <p class="copyright">© 2025 Accomfy</p>
</footer>

</body>
</html>
