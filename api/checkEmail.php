<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__."/../config.php";

header("Content-Type:application/json");

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data['email'])){
    echo json_encode(["error" => "No email given"]);
    exit;
}

$email = trim($data['email']);
try{

    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
}
catch(Exception $e){
    echo json_encode(["error" => "Database query failed: " . $e->getMessage()]);
    exit;
}

if($count > 0){
    echo json_encode(["available" => false]);
}
else{
    echo json_encode(["available" => true]);
}

$stmt->close();

?>