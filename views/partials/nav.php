<!-- Navigation partial -->
<?php require_once __DIR__ . '/../../includes/session.php'; ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar">
  <div class="nav-container">
    <a href="/accomfy/views/listings/all.php" class="brand">Accomfy</a>
    <ul class="nav-links">
      <?php if (isset($_SESSION['user_id'], $_SESSION['role'])): ?>
        <?php if ($_SESSION['role'] === 'student'): ?>
          <li><a href="/accomfy/views/dashboard/saved-listings.php">Saved Listings</a></li>
        <?php elseif ($_SESSION['role'] === 'landlord' || $_SESSION['role'] === 'admin'): ?>
          <li><a href="/accomfy/views/dashboard/my-listings.php">Manage Listings</a></li>
        <?php endif; ?>
        <li><a href="/accomfy/logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="/accomfy/views/listings/all.php">Home</a></li>
        <li><a href="/accomfy/views/dashboard/create-listing.php">Create a Listing</a></li>
        <li><a class="link-login" href="/accomfy/views/auth/login.php">Login</a></li>
        <li><a class="link-register" href="/accomfy/views/auth/register.php">Register</a></li>
      <?php endif; ?>
    </ul>
  </div>
</nav>
