<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/auth_check.php';

function sanitize($value) {
    return htmlspecialchars(trim($value));
}

function saveUploadedFile($file, $destination_folder) {
    $filename = time() . '_' . basename($file['name']);
    $target_path = $destination_folder . $filename;
    if (move_uploaded_file($file['tmp_name'], __DIR__ . '/../' . $target_path)) {
        return $target_path;
    }
    return false;
}

function saveMultiplePhotos($listing_id, $photo_files) {
    global $pdo;

    $upload_dir = '/accomfy/uploads/photos/';
    $saved_count = 0;

    foreach ($photo_files['tmp_name'] as $index => $tmpName) {
        $file = [
            'name' => $photo_files['name'][$index],
            'tmp_name' => $tmpName
        ];

        $path = saveUploadedFile($file, $upload_dir);
        if ($path) {
            $stmt = $pdo->prepare("INSERT INTO listing_photos (listing_id, photo_path) VALUES (:listing_id, :photo_path)");
            $stmt->execute(['listing_id' => $listing_id, 'photo_path' => $path]);
            $saved_count++;
        }
    }

    return $saved_count;
}

// CREATE LISTING
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create') {
    // Validate photo count
    if (!isset($_FILES['photos']) || count($_FILES['photos']['name']) < 10) {
        die("You must upload at least 10 photos.");
    }

    // Save document (optional)
    $document_path = '';
    if (!empty($_FILES['document']['tmp_name'])) {
        $document_path = saveUploadedFile($_FILES['document'], 'uploads/documents/');
        if (!$document_path) {
            die("Failed to upload document.");
        }
    }

    // Insert into listings table
    $stmt = $pdo->prepare("INSERT INTO listings (
        user_id, title, address, city, province, postal_code, price_per_month,
        available_from, property_type, furnishing, description, is_verified, document_path
    ) VALUES (
        :user_id, :title, :address, :city, :province, :postal_code, :price_per_month,
        :available_from, :property_type, :furnishing, :description, 0, :document_path
    )");

    $stmt->execute([
        'user_id' => $_SESSION['user_id'],
        'title' => sanitize($_POST['title']),
        'address' => sanitize($_POST['address']),
        'city' => sanitize($_POST['city']),
        'province' => sanitize($_POST['province']),
        'postal_code' => sanitize($_POST['postal_code']),
        'price_per_month' => $_POST['price_per_month'],
        'available_from' => $_POST['available_from'],
        'property_type' => $_POST['property_type'],
        'furnishing' => $_POST['furnishing'],
        'description' => sanitize($_POST['description']),
        'document_path' => $document_path
    ]);

    $listing_id = $pdo->lastInsertId();

    $photo_count = saveMultiplePhotos($listing_id, $_FILES['photos']);
    if ($photo_count < 10) {
        die("Upload failed: only $photo_count photos were saved.");
    }

    header("Location: /views/dashboard/my-listings.php");
    exit();
}

// UPDATE LISTING (no photos or documents here — for simplicity)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update') {
    $stmt = $pdo->prepare("UPDATE listings SET
        title = :title,
        address = :address,
        city = :city,
        province = :province,
        postal_code = :postal_code,
        price_per_month = :price_per_month,
        available_from = :available_from,
        description = :description
        WHERE listing_id = :listing_id
        AND user_id = :user_id
    ");

    $stmt->execute([
        'title' => sanitize($_POST['title']),
        'address' => sanitize($_POST['address']),
        'city' => sanitize($_POST['city']),
        'province' => sanitize($_POST['province']),
        'postal_code' => sanitize($_POST['postal_code']),
        'price_per_month' => $_POST['price_per_month'],
        'available_from' => $_POST['available_from'],
        'description' => sanitize($_POST['description']),
        'listing_id' => $_POST['listing_id'],
        'user_id' => $_SESSION['user_id']
    ]);

    header("Location: /views/dashboard/my-listings.php");
    exit();
}

// DELETE LISTING
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'delete') {
    $listing_id = $_GET['id'] ?? null;

    if (!$listing_id) {
        die("Listing ID not provided.");
    }

    // Check ownership/admin
    $check = $pdo->prepare("SELECT * FROM listings WHERE listing_id = :id");
    $check->execute(['id' => $listing_id]);
    $listing = $check->fetch(PDO::FETCH_ASSOC);

    if (!$listing) {
        die("Listing not found.");
    }

    if ($listing['user_id'] !== $_SESSION['user_id'] && $_SESSION['role'] !== 'admin') {
        die("You do not have permission to delete this listing.");
    }

    // Delete photos
    $photo_stmt = $pdo->prepare("SELECT photo_path FROM listing_photos WHERE listing_id = :id");
    $photo_stmt->execute(['id' => $listing_id]);
    $photos = $photo_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($photos as $photo) {
        $file_path = __DIR__ . '/../' . $photo['photo_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $pdo->prepare("DELETE FROM listing_photos WHERE listing_id = :id")->execute(['id' => $listing_id]);

    // Delete document
    if (!empty($listing['document_path'])) {
        $doc_path = __DIR__ . '/../' . $listing['document_path'];
        if (file_exists($doc_path)) {
            unlink($doc_path);
        }
    }

    // Delete listing
    $pdo->prepare("DELETE FROM listings WHERE listing_id = :id")->execute(['id' => $listing_id]);

    header("Location: /views/dashboard/my-listings.php");
    exit();
}
