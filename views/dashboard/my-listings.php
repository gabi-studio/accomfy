<?php
require_once '../../includes/session.php';
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['role'] === 'admin';

$sql = $is_admin
    ? "SELECT * FROM listings ORDER BY created_at DESC"
    : "SELECT * FROM listings WHERE user_id = :user_id ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
if (!$is_admin) {
    $stmt->execute(['user_id' => $user_id]);
} else {
    $stmt->execute();
}
$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saved Listings</title>
    <link rel="stylesheet" href="/accomfy/public/css/styles.css">
</head>
<body>

<h2>My Listings</h2>
<a href="create-listing.php" class="btn">+ Add New Listing</a>

<?php foreach ($listings as $listing): ?>
    <div class="listing-card">
        <h3><?= htmlspecialchars($listing['title']) ?></h3>
        <p><?= htmlspecialchars($listing['address']) ?> — $<?= $listing['price_per_month'] ?>/month</p>
        <p><a href="edit-listing.php?id=<?= $listing['listing_id'] ?>">Edit</a> |
        <a href="/controllers/ListingController.php?action=delete&id=<?= $listing['listing_id'] ?>" onclick="return confirm('Are you sure you want to delete this listing?')">Delete</a>

    </div>
<?php endforeach; ?>


<!-- <a href="/accomfy/index.php" class="btn">← Back to Listings</a> -->

</body>
</html>

