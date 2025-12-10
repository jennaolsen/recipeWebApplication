<?php
    require "../config.php";
    session_start();

    $data = json_decode(file_get_contents('php://input'), true);
    if(!isset($_SESSION['user_id'])){
        echo json_encode(["error" => "You must be logged in to post comments."]);
        exit;
    }

    $recipe_id = intval($data['recipe_id'] ?? 0);
    $user_id = $_SESSION['user_id'];
    $comment_text = trim($data['comment_text'] ?? '');

    $stmt = $conn->prepare("INSERT INTO comments (recipe_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $recipe_id, $user_id, $comment_text);
    $stmt->execute();

    echo json_encode(["message" => "Comment added"]);
?>