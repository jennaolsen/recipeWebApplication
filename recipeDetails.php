<?php
error_reporting(E_ALL);

require __DIR__ . "/config.php";
require __DIR__ . '/helperPHP/apiFunctions.php';

if (!isset($_GET['id'])) {
    echo "No recipe ID provided.";
    exit;
}
$recipeID = (int)$_GET['id'];
$stmt = $conn->prepare("Select * FROM recipeDetails WHERE spoonacular_id = ?");
$stmt->bind_param("i", $recipeID);
$stmt->execute();
$result = $stmt->get_result();
$dbRecipe = $result->fetch_assoc();
$stmt->close();
if($dbRecipe){
    $title = $dbRecipe['title'];
    $imageURL = $dbRecipe['image_url'];
    $ready = $dbRecipe['ready_in_minutes'];
    $servings = $dbRecipe['servings'];
    $summary = $dbRecipe['summary'];
    $instructions = $dbRecipe['instructions'];
    $ingredients = $dbRecipe['ingredients'] ? json_decode($dbRecipe['ingredients'], true) : [];
}
else{
    $recipe = getRecipeDetails($apiKey, $recipeID);
    $title = $recipe['title'] ?? "";
    $imageURL = $recipe['image'] ??"";
    $ready = $recipe['readyInMinutes'] ?? "";
    $servings = $recipe['servings'] ?? "";
    $summary = $recipe['summary'] ?? "";
    $ingredients = $recipe['extendedIngredients'] ?? [];
    $instructions = $recipe['instructions'] ?? "";
    if($instructions == ""){
        $sourceURL = $recipe['sourceUrl'] ?? "";
        $instructions = "Instructions are available at $sourceURL";
    }
    $stmt = $conn->prepare("
        INSERT INTO recipeDetails (spoonacular_id, title, image_url, ready_in_minutes, servings, summary, instructions, ingredients)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $ingredientsJSON = json_encode($ingredients);
    $stmt->bind_param("isssiiss", $recipeID, $title, $imageURL, $ready, $servings, $summary, $instructions, $ingredientsJSON);
    $stmt->execute();
    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class ="max-w-4xl mx-auto p-6 bg-white shadow rounded-xl mt-10">
        <div class = "flex items-center justify-between mb-6">
            <h1 class = "text-3xl font-bold mb-4"><?php echo htmlspecialchars($title); ?></h1>
            <?php if(isset($_SESSION['user_id'])): ?>
                <form class= "flex justify-end" method="post" action="helperPHP/saveRecipe.php" >
                    <input type="hidden" name="recipe_id" value="<?php echo $recipeID; ?>">
                    <button type = "submit" class="btn px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow"> Save Recipe</button>
                </form>
            <?php endif; ?>
        </div>
        <img src="<?php echo htmlspecialchars($imageURL); ?>" alt="<?php echo htmlspecialchars($title); ?>" class="w-full h-auto mb-6 rounded-lg">
        <h2 class="text-2xl font-semibold mb-3">Ingredients</h2>
        <ul class="list-disc list-inside mb-6">
            <?php foreach ($ingredients as $ingredient): ?>
                <li><?php echo htmlspecialchars($ingredient['original']); ?></li>
            <?php endforeach; ?>
        </ul>
        <h2 class="text-xl font-semibold mb-2">Instructions</h2>
        <p class="leading-relaxed">
            <?php echo $instructions; ?>
        </p>
    </div>

    <div id="comments">
        <h3 class="text-2xl font-bold text-center my-6">Comments</h3>
        <div id="comments-list" class="max-w-4xl mx-auto mb-6">
            <!-- Comments will be loaded here -->
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
        <textarea id="commentInput" class="max-w-4xl mx-auto mb-6" placeholder="Add a comment..."></textarea>
            <button id="submitComment" class="btn px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow mb-10" onclick="submitComment(RECIPE_ID)">Submit Comment</button>
        <?php else: ?>
            <p class="text-center mb-10">Please <a href="loginPage.php?redirect=<?= urlencode($_SERVER['REQUEST_URI'])?>" class="text-pink-600 hover:underline">log in</a> to leave a comment.</p>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
    <script>
        const RECIPE_ID = <?php echo $recipeID; ?>;
    </script>

    <script src="js/leaveComments.js"></script>
</body>
</html>