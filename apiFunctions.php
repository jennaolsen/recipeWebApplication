<?php

require __DIR__ . "/config.php";

function loadDatabaseRecipes($recipes, $conn){
        $stmt = $conn->prepare("
        INSERT IGNORE INTO recipes (spoonacular_id, title, image_url)
        VALUES (?, ?, ?)
    ");
    foreach ($recipes as $recipe) {
        $spoonacularID = $recipe['id'];
        $title = $recipe['title'];
        $image = $recipe['image'];

        $stmt->bind_param("iss", $spoonacularID, $title, $image);
        $stmt->execute();

    }
    $stmt->close();
}

function fetchRecipes($apiKey, $query, $number = 10, $pageNumber=1) {
    $offset = ($pageNumber - 1) * $number;
    $url = 'https://api.spoonacular.com/recipes/complexSearch?apiKey=' . $apiKey
        . '&query=' . urlencode($query)
        . '&number=' . $number
        . '&offset=' . $offset;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $recipe = curl_exec($ch);
    if ($recipe === false) {
        curl_close($ch);
        return [];
    }

    curl_close($ch);

    $data = json_decode($recipe, true);
    
    return $data['results'] ?? [];
}

function recipesForDisplay($conn, $offset=0, $number=20){
    $sql = "SELECT spoonacular_id, title, image_url FROM recipes ORDER BY id ASC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $number, $offset);
    $stmt->execute();

    $result = $stmt->get_result();
    $recipes = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    return $recipes;
}
function getRecipeDetails($apiKey, $id){
    $url = "https://api.spoonacular.com/recipes/$id/information?apiKey=$apiKey";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $recipe = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($recipe, true);
    return $data;
}
?>