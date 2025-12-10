<?php
require __DIR__ . "/config.php";
$stmt = $conn->prepare("SELECT * FROM recipes ORDER BY times_visited DESC LIMIT 10");
$stmt->execute();
$result = $stmt->get_result();
$popularRecipes = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>The Recipe Spot</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="styles.css">
    </head>
        <body>
            <?php include 'header.php'; ?>
            <?php include 'authForms.php'; ?>
            <main>
                <section class = "text-center my-8">
                    <h2 class="text-2xl sm:text-4xl md:text-5xl font-extrabold text-teal-950 mb-4">Welcome to The Recipe Spot!</h2>
                    <p class="text-lg sm:text-xl text-gray-700 max-w-2xl mx-auto">Here you can find a number of easy and delicious recipes! Be sure to create an account to save your favorites!</p>
                </section>      
                <h3 class="text-3xl font-bold text-center my-6">Popular Recipes</h3>
                    <div class = "w-full flex justify-center px-4">
                        <div class = "flex flex-wrap justify-center gap-6 max-w-7xl w-full">
                            <?php foreach ($popularRecipes as $recipe): ?>
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
                
        
            </main>
            <?php include 'footer.php'; ?>
        </body>
    <script src="script.js"></script>

</html>