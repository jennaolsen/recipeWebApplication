<?php

require __DIR__ . "/config.php";

function loadDatabaseRecipes($recipes, $conn){
        $stmt = $conn->prepare("
        INSERT INTO recipes (spoonacular_id, title, image_url)
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

function fetchRecipes($apiKey, $query = '', $number=10) {

    https://api.spoonacular.com/recipes/complexSearch?apiKey=2adad6ace9d14f499d0b0d1521ca7a1f&query=pasta&maxFat=25&number=2
   
    $url = 'https://api.spoonacular.com/recipes/complexSearch?apiKey=' . $apiKey . '&number=' . $number;

    if ($query !== '') {
        $url .= '&query=' . urlencode($query);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) return [];

    $data = json_decode($response, true);
    return $data['results'] ?? [];
}

function recipesForDisplay(){
    $sql = "SELECT spoonacular_id, title, image_url FROM recipes ORDER BY created_at DESC LIMIT 20";
    $result = $conn->query($sql);

    $recipes = [];
    if ($result){
        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }
    }
}
?>