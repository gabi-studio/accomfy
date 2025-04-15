<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/session.php';


$listing_id = $_GET['id'] ?? null;
if (!$listing_id) {
    die('Listing ID not provided.');
}

$stmt = $pdo->prepare("SELECT l.*, u.first_name, u.last_name, u.email
                       FROM listings l
                       JOIN users u ON l.user_id = u.user_id
                       WHERE l.listing_id = :id AND l.is_verified = 1");
$stmt->execute(['id' => $listing_id]);
$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    die('Listing not found or not verified.');
}

$photo_stmt = $pdo->prepare("SELECT * FROM listing_photos WHERE listing_id = :id");
$photo_stmt->execute(['id' => $listing_id]);
$photos = $photo_stmt->fetchAll(PDO::FETCH_ASSOC);

// Save listing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_listing'])) {
    if ($_SESSION['role'] === 'student') {
        $check = $pdo->prepare("SELECT * FROM saved_listings WHERE user_id = :user_id AND listing_id = :listing_id");
        $check->execute([
            'user_id' => $_SESSION['user_id'],
            'listing_id' => $listing_id
        ]);

        if (!$check->fetch()) {
            $insert = $pdo->prepare("INSERT INTO saved_listings (user_id, listing_id) VALUES (:user_id, :listing_id)");
            $insert->execute([
                'user_id' => $_SESSION['user_id'],
                'listing_id' => $listing_id
            ]);
            $save_message = "Listing saved!";
        } else {
            $save_message = "You already saved this listing.";
        }
    }
}

// Contact form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact'])) {
    $message = htmlspecialchars($_POST['message']);
    $sender = htmlspecialchars($_POST['sender_email']);
    $contact_message = "Thank you! Your message was sent to the poster.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($listing['title']) ?> - Accomfy</title>
  <link rel="stylesheet" href="/accomfy/public/css/styles.css">
  <link rel="stylesheet" href="/accomfy/public/css/single.css">
  

</head>
<body>

<?php include_once __DIR__ . '/../../views/partials/nav.php'; ?>




<div class="single-container">
  <h1 class="listing-title"><?= htmlspecialchars($listing['title']) ?></h1>

  
  <?php if ($photos): ?>
  <div class="photo-gallery">
    <?php foreach ($photos as $photo): ?>
      <img 
        src="/accomfy/<?= htmlspecialchars($photo['photo_path']) ?>" 
        alt="Photo" 
        class="photo-item"
        onclick="openLightbox(this.src)"
      >
    <?php endforeach; ?>
    </div>
    <?php endif; ?>


<div id="lightbox" class="lightbox" onclick="closeLightbox()">
  <span class="lightbox-close" onclick="closeLightbox()">×</span>
  <img id="lightbox-img" class="lightbox-content" alt="Enlarged Photo">
</div>

<div class="listing-container">
    <div class="listing-info">
    

        <!-- Basic Info -->
        <div class="section">
            <h3>Listing Info</h3>
            <p><strong>Posted by:</strong> <?= htmlspecialchars($listing['first_name'] . ' ' . $listing['last_name']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($listing['location_type'] ?? 'N/A') ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($listing['city'] ?? 'N/A') ?>, <?= htmlspecialchars($listing['province'] ?? '') ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($listing['address'] ?? 'N/A') ?></p>
            <p><strong>Postal Code:</strong> <?= htmlspecialchars($listing['postal_code'] ?? 'N/A') ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($listing['price_per_month']) ?>/month</p>
            <p><strong>Property Type:</strong> <?= htmlspecialchars($listing['property_type']) ?></p>
            <p><strong>Furnishing:</strong> <?= htmlspecialchars($listing['furnishing']) ?></p>
            <p><strong>Bedrooms:</strong> <?= htmlspecialchars($listing['bedrooms']) ?></p>
            <p><strong>Bathrooms:</strong> <?= htmlspecialchars($listing['bathrooms']) ?></p>
            <p><strong>Available From:</strong> <?= htmlspecialchars($listing['available_from']) ?></p>
            <p><strong>Distance to Campus:</strong> <?= htmlspecialchars($listing['distance_to_campus_km']) ?> km</p>
        </div>

        <!-- Description -->
        <div class="section">
            <h3>Description</h3>
            <p><?= nl2br(htmlspecialchars($listing['description'])) ?></p>
        </div>

        <!-- Amenities -->
        <div class="section">
            <h3>Amenities</h3>
            <p><strong>Utilities Included:</strong> <?= $listing['utilities_included'] ? 'Yes' : 'No' ?></p>
            <p><strong>Internet Included:</strong> <?= $listing['internet_included'] ? 'Yes' : 'No' ?></p>
            <p><strong>Laundry In-Unit:</strong> <?= $listing['laundry_in_unit'] ? 'Yes' : 'No' ?></p>
            <p><strong>Parking Available:</strong> <?= $listing['parking_available'] ? 'Yes' : 'No' ?></p>
            <p><strong>Wheelchair Accessible:</strong> <?= $listing['wheelchair_accessible'] ? 'Yes' : 'No' ?></p>
        </div>

        <!-- Rules -->
        <div class="section">
            <h3>Rules</h3>
            <p><strong>Pet Friendly:</strong> <?= $listing['pet_friendly'] ? 'Yes' : 'No' ?></p>
            <p><strong>Smoking Allowed:</strong> <?= $listing['smoking_allowed'] ? 'Yes' : 'No' ?></p>
        </div>

        
    </div>



    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'student'): ?>
        <form method="POST" class="save-form">
        <button type="submit" name="save_listing" class="btn">❤️ Save Listing</button>
        </form>
        <?php if (!empty($save_message)) echo "<p>$save_message</p>"; ?>
    <?php endif; ?>

    <div class="contact-form">
        <h3>Contact the Poster</h3>
        <form method="POST">
        <label>Your Email:
            <input type="email" name="sender_email" required>
        </label>
        <label>Message:
            <textarea name="message" rows="5" required></textarea>
        </label>
        <button type="submit" name="contact" class="btn">Send Message</button>
        </form>
        <?php if (!empty($contact_message)) echo "<p>$contact_message</p>"; ?>
    </div>

    <a href="all.php" class="btn back-link">← Back to Listings</a>
</div>

<script src="/accomfy/public/js/lightbox.js" defer></script>
</body>
</html>
