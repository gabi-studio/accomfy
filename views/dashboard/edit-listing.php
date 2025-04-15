<?php
require_once '../../includes/session.php';
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$listing_id = $_GET['id'] ?? null;
if (!$listing_id) { die("Missing listing ID."); }

$stmt = $pdo->prepare("SELECT * FROM listings WHERE listing_id = :id");
$stmt->execute(['id' => $listing_id]);
$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) { die("Listing not found."); }
?>

<h2>Edit Listing</h2>
<form action="/controllers/ListingController.php" method="POST">
    <input type="hidden" name="action" value="update">
    <input type="hidden" name="listing_id" value="<?= $listing_id ?>">

    <label>Title:<input type="text" name="title" value="<?= htmlspecialchars($listing['title']) ?>" required></label><br>
    <label>Address:<input type="text" name="address" value="<?= htmlspecialchars($listing['address']) ?>" required></label><br>
    <label>City:<input type="text" name="city" value="<?= htmlspecialchars($listing['city']) ?>" required></label><br>
    <label>Province:<input type="text" name="province" value="<?= htmlspecialchars($listing['province']) ?>" required></label><br>
    <label>Postal Code:<input type="text" name="postal_code" value="<?= htmlspecialchars($listing['postal_code']) ?>" required></label><br>
    <label>Price Per Month:<input type="number" name="price_per_month" value="<?= htmlspecialchars($listing['price_per_month']) ?>" required></label><br>
    <label>Available From:<input type="date" name="available_from" value="<?= htmlspecialchars($listing['available_from']) ?>" required></label><br>
    <label>Description:<br>
        <textarea name="description" rows="5" required><?= htmlspecialchars($listing['description']) ?></textarea>
    </label><br>

    <button type="submit" class="btn">Update Listing</button>
</form>
