<?php
session_start();

if ( $_SERVER['REQUEST_METHOD'] === 'GET') {
    require __DIR__ . "/config.php";
    if(!isset($_SESSION['user_id'])){
        header("Location: loginPage.php");
        exit;
    }
    $userID = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT username, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($username, $createdAt);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT r.* FROM recipes r
                            INNER JOIN saved_recipes sr ON r.spoonacular_id = sr.recipe_id
                            WHERE sr.user_id = ?"
                            );
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $recipe = array_rand($recipes, 1);
    
    header('Content-Type: application/json');
    echo json_encode(['recipeId' => $recipes[$recipe]['spoonacular_id']]);
    exit;
}

http_response_code(403);
echo json_encode(['message' => 'Forbidden']);
exit;

?>