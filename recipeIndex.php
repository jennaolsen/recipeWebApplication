<?php
require __DIR__ . "/config.php";
require 'apiFunctions.php';
$recipes = fetchRecipes($apiKey,'', 20);

loadDatabaseRecipes($recipes, $conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recipe Index</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h2>Recipe Index</h2>
        <div class="recipe-grid">
            <?php foreach ($recipes as $recipe): ?>
                <div class="recipe-card">
                    <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>">
                    <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                    <a href="recipePage.php?id=<?= $recipe['spoonacular_id'] ?>">View Recipe</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include 'authForms.php'; ?>
    <?php include 'footer.php'; ?>
</body>
<script src="script.js"></script>

</html>