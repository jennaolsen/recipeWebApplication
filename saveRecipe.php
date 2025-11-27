<?php

require __DIR__ . "/config.php";
session_start();
if(!isset($_SESSION['user_id'])){
    die("You must be logged in to save recipes.");
}
if(isset($_POST['recipe_id'])){
    $recipeID = intval($_POST['recipe_id']);
    $userID = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT IGNORE INTO saved_recipes (user_id, recipe_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userID, $recipeID);
    $stmt->execute();
    $stmt->close();

    header("Location: recipeDetails.php?id=$recipeID&saved=1");
    exit;
}
?>