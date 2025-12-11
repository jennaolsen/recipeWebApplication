<?php
require __DIR__ . "/../config.php";

if(!isset($_POST['recipeId'])) exit;
$recipeID = (int)$_POST['recipeId'];
$stmt = $conn->prepare("UPDATE recipes SET times_visited = times_visited + 1 WHERE spoonacular_id = ?");
$stmt->bind_param("i", $recipeID);
$stmt->execute();
$stmt->close();

http_response_code(200);
?>