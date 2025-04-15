<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/FilterController.php';


$filters = [
    'property_type' => $_GET['propertyType'] ?? '',
    'price' => $_GET['price'] ?? '',
    'location_type' => $_GET['location'] ?? '',
    'furnishing' => $_GET['furnishing'] ?? '',
    'bedroom' => $_GET['bedroom'] ?? '',
    'bathroom' => $_GET['bathroom'] ?? '',
    'utilities' => isset($_GET['utilities']),
    'internet' => isset($_GET['internet']),
    'laundry' => isset($_GET['laundry']),
    'parking' => isset($_GET['parking']),
    'pet_friendly' => isset($_GET['pet_friendly']),
    'smoking' => isset($_GET['smoking']),
    'wheelchair' => isset($_GET['wheelchair']),
    'match_only' => isset($_GET['match_only']),
    'user_id' => $_SESSION['user_id'] ?? null,
    'page' => $_GET['page'] ?? 1

];

$result = getFilteredListings($pdo, $filters);
$listings = $result['listings'];
$total = $result['total'];
$page = $result['page'];
$per_page = $result['per_page'];
$total_pages = ceil($total / $per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Accomfy - Find Accommodation</title>
  <link rel="stylesheet" href="/accomfy/public/css/styles.css" />
</head>
<body>  

<script>
function toggleFilterPopup() {
  const popup = document.getElementById('filterModal');
  popup.classList.toggle('show');
}
</script>


<?php include_once __DIR__ . '/../../views/partials/nav.php'; ?>


<!-- Hero Section -->
<div class="hero-container">
  <img src="/accomfy/public/images/accomfy-hero.png" class="hero-image" alt="Student Accommodation">
  <div class="hero-text">
    <h1>Accomfy: Comfort in your Accommodation</h1>
    <p>Search for the perfect student accommodation that fits your budget and preferences.</p>
  </div>

  <!-- Search Form -->
  <div class="search-container">
    <form method="GET" class="search-form">
      <input type="text" name="search" class="search-field" placeholder="Search by Location, Price, or Property Type" />
      <button type="submit" class="btn">Search</button>
    </form>
  </div>
</div>

<div style="text-align: center; margin-top: 1rem;">
  <button type="button" class="btn" onclick="toggleFilterPopup()">🔍 Show Filters</button>
</div>



<!-- Popup Filter Modal -->
<div id="filterModal" class="filter-popup">
  <div class="filter-content">
    <span class="close-btn" onclick="toggleFilterPopup()">×</span>
    <h2>Filter Listings</h2>

    <form method="GET" class="filter-form">
      <div class="filters">

        <!-- Keep all your existing filter fields here (selects + checkboxes) -->
        <!-- ... your existing filter code ... -->
        <div class="options">
        <label for="propertyType">Property Type:</label>
        <select name="propertyType" class="filter-field">
            <option value="">All</option>
            <option value="apartment">Apartment</option>
            <option value="house">House</option>
            <option value="dorm">Dorm</option>
            <option value="shared room">Shared Room</option>
        </select>
        </div>

        <div class="options">
        <label for="price">Price:</label>
        <select name="price" class="filter-field">
            <option value="">Any</option>
            <option value="500">$500</option>
            <option value="750">$750</option>
            <option value="1000">$1000</option>
            <option value="1500">$1500</option>
        </select>
        </div>

        <div class="options">
        <label for="location">Location:</label>
        <select name="location" class="filter-field">
            <option value="">All</option>
            <option value="downtown">Downtown</option>
            <option value="suburbs">Suburbs</option>
            <option value="near campus">Near Campus</option>
        </select>
        </div>

        <div class="options">
        <label for="furnishing">Furnishing:</label>
        <select name="furnishing" class="filter-field">
            <option value="">All</option>
            <option value="furnished">Furnished</option>
            <option value="semi-furnished">Semi-Furnished</option>
            <option value="unfurnished">Unfurnished</option>
        </select>
        </div>

        <!-- New Filters -->
        <div class="options">
        <label for="bedrooms">Bedrooms:</label>
        <select name="bedrooms" class="filter-field">
            <option value="">Any</option>
            <option value="1">1+</option>
            <option value="2">2+</option>
            <option value="3">3+</option>
            <option value="4">4+</option>
            <option value="5">5+</option>
        </select>
        </div>

        <div class="options">
        <label for="bathrooms">Bathrooms:</label>
        <select name="bathroom" class="filter-field">
            <option value="">Any</option>
            <option value="1">1+</option>
            <option value="2">2+</option>
            <option value="3">3+</option>
            <option value="4">4+</option>
            <option value="5">5+</option>
        </select>
        </div>

        <div class="options">
        <label><input type="checkbox" name="utilities" <?= isset($_GET['utilities']) ? 'checked' : '' ?>> Utilities Included</label>
        </div>

        <div class="options">
        <label><input type="checkbox" name="internet" <?= isset($_GET['internet']) ? 'checked' : '' ?>> Internet Included</label>
        </div>
        
        <div class="options">
        <label><input type="checkbox" name="laundry" <?= isset($_GET['laundry']) ? 'checked' : '' ?>> Laundry in Unit</label>
        </div>

        <div class="options">
        <label><input type="checkbox" name="parking" <?= isset($_GET['parking']) ? 'checked' : '' ?>> Parking Available</label>
        </div>

        <div class="options">
        <label><input type="checkbox" name="pet_friendly" <?= isset($_GET['pet_friendly']) ? 'checked' : '' ?>> Pet Friendly</label>
        </div>

        <div class="options">
        <label><input type="checkbox" name="smoking" <?= isset($_GET['smoking']) ? 'checked' : '' ?>> Smoking Allowed</label>
        </div>

        <div class="options">
        <label><input type="checkbox" name="wheelchair" <?= isset($_GET['wheelchair']) ? 'checked' : '' ?>> Wheelchair Accessible</label>
        </div>


        <!-- <div class="options">
        <label>
            <input type="checkbox" name="match_only" <?= isset($_GET['match_only']) ? 'checked' : '' ?> />
            🔗 Match my preferences
        </label>
        </div> -->

        <button type="submit" class="btn">Filter</button>

        <button type="submit" class="btn">Apply Filters</button>
      </div>
    </form>
  </div>
</div>


<!-- Filters -->
<form method="GET" class="filter-form">
  <div class="filters">
    <div class="options">
      <label for="propertyType">Property Type:</label>
      <select name="propertyType" class="filter-field">
        <option value="">All</option>
        <option value="apartment">Apartment</option>
        <option value="house">House</option>
        <option value="dorm">Dorm</option>
        <option value="shared room">Shared Room</option>
      </select>
    </div>

    <div class="options">
      <label for="price">Price:</label>
      <select name="price" class="filter-field">
        <option value="">Any</option>
        <option value="500">$500</option>
        <option value="750">$750</option>
        <option value="1000">$1000</option>
        <option value="1500">$1500</option>
      </select>
    </div>

    <div class="options">
      <label for="location">Location:</label>
      <select name="location" class="filter-field">
        <option value="">All</option>
        <option value="downtown">Downtown</option>
        <option value="suburbs">Suburbs</option>
        <option value="near campus">Near Campus</option>
      </select>
    </div>

    <div class="options">
      <label for="furnishing">Furnishing:</label>
      <select name="furnishing" class="filter-field">
        <option value="">All</option>
        <option value="furnished">Furnished</option>
        <option value="semi-furnished">Semi-Furnished</option>
        <option value="unfurnished">Unfurnished</option>
      </select>
    </div>

    <!-- New Filters -->
    <div class="options">
    <label for="bedrooms">Bedrooms:</label>
    <select name="bedrooms" class="filter-field">
        <option value="">Any</option>
        <option value="1">1+</option>
        <option value="2">2+</option>
        <option value="3">3+</option>
        <option value="4">4+</option>
        <option value="5">5+</option>
    </select>
    </div>

    <div class="options">
    <label for="bathrooms">Bathrooms:</label>
    <select name="bathroom" class="filter-field">
        <option value="">Any</option>
        <option value="1">1+</option>
        <option value="2">2+</option>
        <option value="3">3+</option>
        <option value="4">4+</option>
        <option value="5">5+</option>
    </select>
    </div>

    <div class="options">
    <label><input type="checkbox" name="utilities" <?= isset($_GET['utilities']) ? 'checked' : '' ?>> Utilities Included</label>
    </div>

    <div class="options">
    <label><input type="checkbox" name="internet" <?= isset($_GET['internet']) ? 'checked' : '' ?>> Internet Included</label>
    </div>
    
    <div class="options">
    <label><input type="checkbox" name="laundry" <?= isset($_GET['laundry']) ? 'checked' : '' ?>> Laundry in Unit</label>
    </div>

    <div class="options">
    <label><input type="checkbox" name="parking" <?= isset($_GET['parking']) ? 'checked' : '' ?>> Parking Available</label>
    </div>

    <div class="options">
    <label><input type="checkbox" name="pet_friendly" <?= isset($_GET['pet_friendly']) ? 'checked' : '' ?>> Pet Friendly</label>
    </div>

    <div class="options">
    <label><input type="checkbox" name="smoking" <?= isset($_GET['smoking']) ? 'checked' : '' ?>> Smoking Allowed</label>
    </div>

    <div class="options">
    <label><input type="checkbox" name="wheelchair" <?= isset($_GET['wheelchair']) ? 'checked' : '' ?>> Wheelchair Accessible</label>
    </div>


    <!-- <div class="options">
      <label>
        <input type="checkbox" name="match_only" <?= isset($_GET['match_only']) ? 'checked' : '' ?> />
        🔗 Match my preferences
      </label>
    </div> -->

    <button type="submit" class="btn">Filter</button>
  </div>
</form>

<!-- Listings -->
<div class="main-container">
    <div class="listings-container">
    <?php foreach ($listings as $listing): ?>
    <?php
        $photo_stmt = $pdo->prepare("SELECT photo_path FROM listing_photos WHERE listing_id = :id LIMIT 1");
        $photo_stmt->execute(['id' => $listing['listing_id']]);
        $photo = $photo_stmt->fetchColumn();
        $photo_url = $photo ? '/accomfy/' . $photo : '/accomfy/public/assets/no-image.png'; // fallback
    ?>
     <a href="/accomfy/views/listings/single.php?id=<?= $listing['listing_id'] ?>" class="listing-card" style="text-decoration: none; color: inherit;">
            <div class="card">
            <img src="<?= $photo_url ?>" alt="Listing Photo" class="card-image">
            <div class="card-body">
                <h2><?= htmlspecialchars($listing['title']) ?></h2>
                <!-- <p>Location: <?= htmlspecialchars($listing['location_type'] ?? 'N/A') ?></p> -->
                <p> $<?= htmlspecialchars($listing['price_per_month']) ?>/month</p>
                <!-- <p>Type: <?= htmlspecialchars($listing['property_type']) ?></p> -->
                <!-- <p>Furnishing: <?= htmlspecialchars($listing['furnishing']) ?></p> -->
                <p>Available from: <?= htmlspecialchars($listing['available_from']) ?></p>
                <?php if (isset($listing['match_score'])): ?>
                <p style="color: green;">💯 Match Score: <?= $listing['match_score'] ?>%</p>
                <?php endif; ?>
            </div>
    </div>
                
    </a>
    <?php endforeach; ?>
    </div>

    <div class="pagination-container">
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div style="text-align: center; margin-top: 2rem;">
            <?php if ($page > 1): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>" class="btn">← Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                class="btn"
                style="<?= $i == $page ? 'font-weight: bold;' : '' ?>">
                <?= $i ?>
            </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>" class="btn">Next →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        </div>
    </div>
    

</div>


</body>

<?php include_once __DIR__ . '/../../views/partials/footer.php'; ?>
</html>
