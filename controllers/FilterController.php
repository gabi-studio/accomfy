<?php
function getFilteredListings($pdo, $filters) {
    $conditions = ["is_verified = 1"];
    $params = [];

    // Core filters
    if (!empty($filters['property_type'])) {
        $conditions[] = "property_type = :property_type";
        $params['property_type'] = $filters['property_type'];
    }

    if (!empty($filters['location_type'])) {
        $conditions[] = "location_type = :location_type";
        $params['location_type'] = $filters['location_type'];
    }

    if (!empty($filters['furnishing'])) {
        $conditions[] = "furnishing = :furnishing";
        $params['furnishing'] = $filters['furnishing'];
    }

    if (!empty($filters['price'])) {
        $conditions[] = "price_per_month <= :price";
        $params['price'] = $filters['price'];
    }

    if (!empty($filters['bedroom'])) {
        $conditions[] = "bedroom >= :bedroom";
        $params['bedroom'] = $filters['bedroom'];
    }

    if (!empty($filters['bathroom'])) {
        $conditions[] = "bathrooms >= :bathroom";
        $params['bathroom'] = $filters['bathroom'];
    }

    // Boolean flags
    if (!empty($filters['utilities'])) {
        $conditions[] = "utilities_included = 1";
    }

    if (!empty($filters['internet'])) {
        $conditions[] = "internet_included = 1";
    }

    if (!empty($filters['laundry'])) {
        $conditions[] = "laundry_in_unit = 1";
    }

    if (!empty($filters['parking'])) {
        $conditions[] = "parking_available = 1";
    }

    if (!empty($filters['pet_friendly'])) {
        $conditions[] = "pet_friendly = 1";
    }

    if (!empty($filters['smoking'])) {
        $conditions[] = "smoking_allowed = 1";
    }

    if (!empty($filters['wheelchair'])) {
        $conditions[] = "wheelchair_accessible = 1";
    }

    // Matchmaking
    if (!empty($filters['match_only']) && !empty($filters['user_id'])) {
        $pref_stmt = $pdo->prepare("
            SELECT preferred_location, preferred_furnishing 
            FROM users 
            WHERE user_id = :id
        ");
        $pref_stmt->execute(['id' => $filters['user_id']]);
        $prefs = $pref_stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($prefs)) {
            if (!empty($prefs['preferred_location'])) {
                $conditions[] = "location_type = :pref_location";
                $params['pref_location'] = $prefs['preferred_location'];
            }

            if (!empty($prefs['preferred_furnishing'])) {
                $conditions[] = "furnishing = :pref_furnishing";
                $params['pref_furnishing'] = $prefs['preferred_furnishing'];
            }
        }
    }

    // Pagination
    $per_page = 9;
    $page = max(1, intval($filters['page'] ?? 1));
    $offset = ($page - 1) * $per_page;

    $where = implode(" AND ", $conditions);
    $sql = "SELECT * FROM listings WHERE $where ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    $stmt->bindValue(":limit", $per_page, PDO::PARAM_INT);
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->execute();

    $listings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count for pagination
    $count_sql = "SELECT COUNT(*) FROM listings WHERE $where";
    $count_stmt = $pdo->prepare($count_sql);
    foreach ($params as $key => $value) {
        $count_stmt->bindValue(":$key", $value);
    }
    $count_stmt->execute();
    $total = $count_stmt->fetchColumn();

    return [
        'listings' => $listings,
        'total' => $total,
        'page' => $page,
        'per_page' => $per_page
    ];
}
