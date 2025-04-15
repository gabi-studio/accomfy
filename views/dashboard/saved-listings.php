<?php
require_once '../../includes/session.php';
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

if ($_SESSION['role'] !== 'student') {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT l.* 
    FROM saved_listings s
    JOIN listings l ON s.listing_id = l.listing_id
    WHERE s.user_id = :user_id
    ORDER BY s.saved_at DESC");
$stmt->execute(['user_id' => $user_id]);
$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper for thumbnail
function getThumbnail($pdo, $listing_id) {
    $stmt = $pdo->prepare("SELECT photo_path FROM listing_photos WHERE listing_id = :id LIMIT 1");
    $stmt->execute(['id' => $listing_id]);
    $photo = $stmt->fetchColumn();
    return $photo ? '/accomfy/' . ltrim($photo, '/') : '/accomfy/public/assets/no-image.png';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saved Listings</title>
    <link rel="stylesheet" href="/accomfy/public/css/styles.css">
    <link rel="stylesheet" href="/accomfy/public/css/single.css">
</head>
<body>

<?php include_once __DIR__ . '/../../views/partials/nav.php'; ?>

<h2 style="text-align: center; padding: 1rem;">My Saved Listings</h2>

<div style="text-align: left; padding: 1rem;">
    <a href="/accomfy/index.php" class="btn">← See All Listings</a>
</div>

<?php if (count($listings) === 0): ?>
    <p style="text-align: center;">You haven't saved any listings yet.</p>
<?php endif; ?>

<div class="listings-container">
    <?php foreach ($listings as $listing): ?>
        <?php $photo_url = getThumbnail($pdo, $listing['listing_id']); ?>
        <div class="listing-card">
            <div class="card">
                <img src="<?= htmlspecialchars($photo_url) ?>" class="card-image" alt="Listing Photo">
                <div class="card-body">
                    <h2><?= htmlspecialchars($listing['title']) ?></h2>
                    <p><strong>City:</strong> <?= htmlspecialchars($listing['city']) ?></p>
                    <p><strong>Type:</strong> <?= htmlspecialchars($listing['property_type']) ?></p>
                    <p><strong>Furnishing:</strong> <?= htmlspecialchars($listing['furnishing']) ?></p>
                    <p><strong>Price:</strong> $<?= htmlspecialchars($listing['price_per_month']) ?>/month</p>

                    <div style="margin-top: 1rem;">
                        <a href="/accomfy/views/listings/single.php?id=<?= $listing['listing_id'] ?>" class="btn">View</a>
                        <a href="/accomfy/controllers/SavedController.php?action=remove&id=<?= $listing['listing_id'] ?>" class="btn" onclick="return confirm('Remove this listing from saved?')">❌ Remove</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>



</body>
</html>
