<?php require_once '../../includes/session.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/accomfy/public/css/styles.css">
    <link rel="stylesheet" href="/accomfy/public/css/login.css">
</head>
<body>

<?php include_once __DIR__ . '/../../views/partials/nav.php'; ?>

<!-- <nav class="navbar">
    <div class="nav-container">
        <a href="/" class="brand">Accomfy</a>
        <ul class="nav-links">
            <li><a href="/index.php">Home</a></li>
            <li><a href="/views/auth/register.php">Register</a></li>
        </ul>
    </div>
</nav> -->

<main class="form-container">
    <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
        <p style="color: green; text-align: center;">Registration successful! Please log in.</p>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid'): ?>
        <p style="color: red; text-align: center;">Invalid email or password.</p>
    <?php endif; ?>

    <form class="registration-form" method="POST" action="/accomfy/controllers/AuthController.php">
        <h2>Login</h2>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" name="login" class="btn">Login</button>
    </form>
</main>

<footer>
    <p class="copyright">© 2025 Accomfy</p>
</footer>

</body>
</html>
