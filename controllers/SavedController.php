<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/db.php';

if ($_SESSION['role'] !== 'student') {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

if ($_GET['action'] === 'remove' && isset($_GET['id'])) {
    $listing_id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM saved_listings WHERE user_id = :user_id AND listing_id = :listing_id");
    $stmt->execute([
        'user_id' => $user_id,
        'listing_id' => $listing_id
    ]);

    header("Location: /accomfy/views/dashboard/saved-listings.php");
    exit();
}
