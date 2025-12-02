<?php
    require __DIR__ . "/config.php";
    require __DIR__ . "/apiFunctions.php";
    if(!isset($_GET['search'] ) || trim($_GET['search']) === ""){
        echo "No search term given.";
        exit;
    }

    $query = trim($_GET['search']);

    $needed = 20;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM recipes where title LIKE ?");
    $likeQuery = '%' . trim($_GET['search']) . '%';
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    $newRecipeIDs = [];

if($count < $needed){
    $fetchedRecipes = complexAPISearch($apiKey, trim($_GET['search']));
    
    foreach($fetchedRecipes as $recipe){
        $newRecipeIDs[] = $recipe['id'];
    }
    loadDatabaseRecipes($fetchedRecipes, $conn);
    $stmt = $conn->prepare("SELECT COUNT(*) FROM recipes where title LIKE ?");
    $likeQuery = '%' . trim($_GET['search']) . '%';
    $stmt->bind_param("s", $likeQuery);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    
}
if($count < $needed && !empty($newRecipeIDs)){
        $recipes = recipesForDisplaybyID($conn, $newRecipeIDs);
    }
else{

    $recipes = complexRecipesForDisplay($conn, trim($_GET['search']), 20);
}
if (empty($recipes)) {
            echo "No recipes found for the search term.";
            exit;
        }



    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h2 class="text-3xl font-bold text-center my-6">Search Results</h2>
        <div class = "w-full flex justify-center px-4">
            <div class = "flex flex-wrap justify-center gap-6 max-w-7xl w-full" id="search-results-container">
                <h2 class="text-3xl font-bold text-center my-6">Results for "<?php echo htmlspecialchars($query); ?>"</h2>
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
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="script.js"></script>

</body>
</html>