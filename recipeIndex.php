<?php
require __DIR__ . "/config.php";
require __DIR__ . '/helperPHP/apiFunctions.php';

$pageNumber = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$recipeNumberPerPage = 20;
$offset = ($pageNumber-1)*$recipeNumberPerPage;
$needed = $offset + $recipeNumberPerPage;


    $stmt = $conn->prepare("SELECT COUNT(*) FROM recipes");
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

$savedCount = $count;

if($count < $needed){
    $apiPage = floor($count / $recipeNumberPerPage) + 1;
    $fetchedRecipes = fetchRecipes($apiKey, "recipe", $recipeNumberPerPage, $apiPage);
    loadDatabaseRecipes($fetchedRecipes, $conn);

    $stmt = $conn->prepare("SELECT COUNT(*) FROM recipes");
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
}

$recipes = recipesForDisplay($conn, $offset, 20);
$totalPages = ceil($count / 20);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recipe Index</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h2 class="text-3xl font-bold text-center my-6">Recipe Index</h2>
        <div class = "w-full flex justify-center px-4">
            <div class = "flex flex-wrap justify-center gap-6 max-w-7xl w-full">
                <?php foreach ($recipes as $recipe): ?>
                    <a href="recipeDetails.php?id=<?php echo $recipe['spoonacular_id']; ?>" 
                        onclick="incrementVisit(event, <?php echo $recipe['spoonacular_id'];?>, this.href)"
                        class="recipe-card-link bg-white rounded-xl shadow hover:shadow-xl transition block overflow-hidden w-[260px]">
                            <img src = "<?php echo $recipe['image_url']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                            </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class = "pageButtons flex justify-center items-center gap-4 py-6">
            <?php if ($pageNumber > 1): ?>
                <a href="recipeIndex.php?page=<?php echo $pageNumber - 1; ?>" class="px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow">Previous</a>
            <?php endif; ?>
            <span class="current_button px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow"> Page <?php echo $pageNumber; ?> </span>
            <a href = "recipeIndex.php?page=<?php echo $pageNumber + 1; ?>" class="px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow">Next Page</a>
        </div>
    </main>
    <?php include 'authForms.php'; ?>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>