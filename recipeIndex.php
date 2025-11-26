<?php
require __DIR__ . "/config.php";
require 'apiFunctions.php';

$pageNumber = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$recipeNumberPerPage = 20;
$offset = ($pageNumber-1)*$recipeNumberPerPage;
$needed = $offset + $recipeNumberPerPage;


    $stmt = $conn->prepare("SELECT COUNT(*) FROM recipes");
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

if($count < $needed){
    $fetchedRecipes = fetchRecipes($apiKey, "recipe", $recipeNumberPerPage, $pageNumber);
    loadDatabaseRecipes($fetchedRecipes, $conn);

     $stmt = $conn->prepare("SELECT COUNT(*) FROM recipes");
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
}
$totalPages = ceil($count / 20);
if($pageNumber <= $count){
    $recipes = recipesForDisplay($conn, $offset, 20);
}
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
        <h2>Recipe Index</h2>
        <div class = "w-full flex justify-center px-4">
            <div class = "flex flex-wrap justify-center gap-6 max-w-7xl w-full">
                <?php foreach ($recipes as $recipe): ?>
                    <a href="recipeDetails.php?id=<?php echo $recipe['spoonacular_id']; ?>" 
                    class="recipe-card-link bg-white rounded-xl shadow hover:shadow-xl transition block overflow-hidden w-[260px]">
                        <img src = "<?php echo $recipe['image_url']; ?>" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($recipe['title']); ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class = "pageButtons">
            <?php if ($pageNumber > 1): ?>
                <a href="recipeIndex.php?page=<?php echo $pageNumber - 1; ?>" class="button">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="recipeIndex.php?page=<?php echo $i; ?>" class="button <?php if ($i == $pageNumber) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </main>
    <?php include 'authForms.php'; ?>
    <?php include 'footer.php'; ?>
</body>
<script src="script.js"></script>

</html>