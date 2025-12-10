<?php
require "../config.php";

$recipe_id = intval($_GET['recipe_id'] ?? 0);
$stmt = $conn->prepare("SELECT c.comment, 
                        DATE_FORMAT(c.created_at, '%b %d, %Y %h:%i %p') AS created_at, 
                        u.username FROM comments c
                        JOIN users u ON u.id = c.user_id
                        WHERE recipe_id = ?
                        ORDER BY c.created_at DESC");

$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_all(MYSQLI_ASSOC));
?>