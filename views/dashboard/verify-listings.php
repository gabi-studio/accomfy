<?php
require_once '../../includes/session.php';
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

// --- Filters ---
$property_type = $_GET['property_type'] ?? '';
$city = $_GET['city'] ?? '';
$furnishing = $_GET['furnishing'] ?? '';

// --- Pagination ---
$limit = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// --- Build WHERE clause ---
$conditions = ["is_verified = 0"];
$params = [];

if (!empty($property_type)) {
    $conditions[] = "property_type = :property_type";
    $params['property_type'] = $property_type;
}
if (!empty($city)) {
    $conditions[] = "city = :city";
    $params['city'] = $city;
}
if (!empty($furnishing)) {
    $conditions[] = "furnishing = :furnishing";
    $params['furnishing'] = $furnishing;
}

$where = implode(" AND ", $conditions);

// --- Get total count ---
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM listings WHERE $where");
$count_stmt->execute($params);
$total = $count_stmt->fetchColumn();
$total_pages = ceil($total / $limit);

// --- Get listings ---
$sql = "SELECT * FROM listings WHERE $where ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue(":$key", $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// --- Helper: First Photo Thumbnail ---
function getThumbnail($pdo, $listing_id) {
    $stmt = $pdo->prepare("SELECT photo_path FROM listing_photos WHERE listing_id = :id LIMIT 1");
    $stmt->execute(['id' => $listing_id]);
    $photo = $stmt->fetchColumn();
    return $photo ? '/' . $photo : '/public/assets/default-thumb.jpg';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Listings | Admin</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>

<h2>🔍 Listings Pending Verification</h2>

<!-- Filters -->
<form method="GET" style="margin-bottom: 1rem; text-align: center;">
    <label>Property Type:
        <select name="property_type">
            <option value="">All</option>
            <option value="apartment" <?= $property_type === 'apartment' ? 'selected' : '' ?>>Apartment</option>
            <option value="house" <?= $property_type === 'house' ? 'selected' : '' ?>>House</option>
            <option value="dorm" <?= $property_type === 'dorm' ? 'selected' : '' ?>>Dorm</option>
            <option value="shared room" <?= $property_type === 'shared room' ? 'selected' : '' ?>>Shared Room</option>
        </select>
    </label>

    <label>City:
        <input type="text" name="city" value="<?= htmlspecialchars($city) ?>">
    </label>

    <label>Furnishing:
        <select name="furnishing">
            <option value="">All</option>
            <option value="furnished" <?= $furnishing === 'furnished' ? 'selected' : '' ?>>Furnished</option>
            <option value="semi-furnished" <?= $furnishing === 'semi-furnished' ? 'selected' : '' ?>>Semi-Furnished</option>
            <option value="unfurnished" <?= $furnishing === 'unfurnished' ? 'selected' : '' ?>>Unfurnished</option>
        </select>
    </label>

    <button type="submit" class="btn">Filter</button>
</form>

<!-- Flash Message -->
<?php if (isset($_GET['success'])): ?>
    <p style="color: green; text-align: center;">✅ Listing verified!</p>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'criteria'): ?>
    <p style="color: red; text-align: center;">⚠️ Must have 10+ photos and a document.</p>
<?php endif; ?>

<!-- Listings -->
<?php if (count($listings) === 0): ?>
    <p style="text-align: center;">No listings match your filters.</p>
<?php endif; ?>

<div class="listings-container">
<?php foreach ($listings as $listing): ?>
    <?php $thumb = getThumbnail($pdo, $listing['listing_id']); ?>
    <div class="listing-card">
        <img src="<?= htmlspecialchars($thumb) ?>" class="card-image" style="height: 150px; object-fit: cover;">
        <div class="card-body">
            <h3><?= htmlspecialchars($listing['title']) ?></h3>
            <p><strong>Type:</strong> <?= htmlspecialchars($listing['property_type']) ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($listing['city']) ?></p>
            <p><strong>Furnishing:</strong> <?= htmlspecialchars($listing['furnishing']) ?></p>
            <p><strong>Rent:</strong> $<?= htmlspecialchars($listing['price_per_month']) ?>/month</p>

            <a href="/controllers/VerifyController.php?id=<?= $listing['listing_id'] ?>"
               onclick="return confirm('Are you sure you want to verify this listing?')"
               class="btn">✅ Verify</a>
        </div>
    </div>
<?php endforeach; ?>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
    <div style="text-align: center; margin-top: 1rem;">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
               style="margin: 0 5px; font-weight: <?= $page == $i ? 'bold' : 'normal' ?>;">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

<a href="/views/dashboard/index.php" class="btn">← Back to Dashboard</a>

</body>
</html>
