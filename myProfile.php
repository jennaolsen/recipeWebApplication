<?php
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

$readableDate = date("F j, Y", strtotime($createdAt));

$stmt = $conn->prepare("SELECT r.* FROM recipes r
                        INNER JOIN saved_recipes sr ON r.spoonacular_id = sr.recipe_id
                        WHERE sr.user_id = ?"
                        );
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$recipes = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h1 class="text-2xl font-bold text-teal-950 text-center my-8">Username: <?php echo htmlspecialchars($username); ?></h1>
        <p class="text-center mb-4">Member since: <?php echo htmlspecialchars($readableDate)?></p>
        <button onclick="randCook()" class = "px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow" type = "button" id="randCook"> What To Make</button>
        <h2 class="text-3xl font-bold text-center my-6">My Saved Recipes</h2>
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
    </main>
    <?php include 'authForms.php'; ?>
    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>

</html>