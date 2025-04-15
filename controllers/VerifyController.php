<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

$listing_id = $_GET['id'] ?? null;
if (!$listing_id) {
    die("No listing ID provided.");
}

// Count photos
$photo_stmt = $pdo->prepare("SELECT COUNT(*) FROM listing_photos WHERE listing_id = :id");
$photo_stmt->execute(['id' => $listing_id]);
$photo_count = $photo_stmt->fetchColumn();

// Check for document
$doc_stmt = $pdo->prepare("SELECT document_path FROM listings WHERE listing_id = :id");
$doc_stmt->execute(['id' => $listing_id]);
$doc_path = $doc_stmt->fetchColumn();

if ($photo_count >= 10 && !empty($doc_path)) {
    $verify = $pdo->prepare("UPDATE listings SET is_verified = 1 WHERE listing_id = :id");
    $verify->execute(['id' => $listing_id]);

    header("Location: /views/dashboard/verify-listings.php?success=1");
    exit();
} else {
    header("Location: /views/dashboard/verify-listings.php?error=criteria");
    exit();
}
