<?php
require_once '../../includes/session.php';
require_once '../../includes/auth_check.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saved Listings</title>
    <link rel="stylesheet" href="/accomfy/public/css/styles.css">
    <link rel="stylesheet" href="/accomfy/public/css/register.css">
</head>


<?php include_once __DIR__ . '/../../views/partials/nav.php'; ?>

<main class = "form-container">
    

    <form class="registration-form" method="POST" action="/controllers/ListingController.php" enctype="multipart/form-data">
        <h2>Create New Listing</h2>
        <input type="hidden" name="action" value="create">

        <div class="form-group">
            <label for="title">Title *</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="address">Address *</label>
            <input type="text" id="address" name="address" required>
        </div>

        <div class="form-group">
            <label for="city">City *</label>
            <input type="text" id="city" name="city" required>
        </div>

        <div class="form-group">
            <label for="province">Province *</label>
            <input type="text" id="province" name="province" required>
        </div>

        <div class="form-group">
            <label for="postal_code">Postal Code *</label>
            <input type="text" id="postal_code" name="postal_code" required>
        </div>

        <div class="form-group">
            <label for="price_per_month">Price Per Month *</label>
            <input type="number" id="price_per_month" name="price_per_month" required>
        </div>

        <div class="form-group">
            <label for="available_from">Available From *</label>
            <input type="date" id="available_from" name="available_from" required>
        </div>

        <div class="form-group">
            <label for="property_type">Property Type *</label>
            <select id="property_type" name="property_type" required>
            <option value="">Select a type</option>
            <option value="apartment">Apartment</option>
            <option value="house">House</option>
            <option value="dorm">Dorm</option>
            <option value="shared room">Shared Room</option>
            </select>
        </div>

        <div class="form-group">
            <label for="furnishing">Furnishing *</label>
            <select id="furnishing" name="furnishing" required>
            <option value="">Select furnishing</option>
            <option value="furnished">Furnished</option>
            <option value="semi-furnished">Semi-Furnished</option>
            <option value="unfurnished">Unfurnished</option>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="5" required></textarea>
        </div>

        <div class="form-group">
            <label for="document">Upload Document</label>
            <input type="file" id="document" name="document">
        </div>

        <div class="form-group">
            <label for="photos">Upload 10+ Photos *</label>
            <input type="file" id="photos" name="photos[]" multiple required>
        </div>

        <button type="submit" class="btn">Create Listing</button>
    </form>

</main>

</body>
</html>
