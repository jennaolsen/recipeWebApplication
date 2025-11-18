
<?php 
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: home.php"); // or dashboard.php
    exit;
}
require __DIR__."/config.php";

$errors = [];


if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $identity = trim($_POST['email']) ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, email, password_hash FROM users WHERE username=? OR email=? LIMIT 1");
    $stmt->bind_param("ss", $identity, $identity);
    $stmt->execute();
    $stmt->bind_result($id, $email, $hash);

        if ($stmt->fetch()) {
            if (password_verify($password, $hash)) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;
                $stmt->close();
                header("Location: home.php");
                exit;
            } else {
                $errors[] = "Could not authenticate user";
            }
        } else {
            // No row found
            $errors[] = "Could not authenticate user";
        }
        $stmt->close();
    }


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Recipe Web Application</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
    </head>
        <body>
            <?php include 'header.php'; ?>
            <?php include 'authForms.php'; ?>
            <main>
                <h2>Welcome to Our Recipe Web Application!</h2>
                <p>Here you can find a number of easy and delicious recipes! Be sure to create an account to save your favorites!</p>
                <form id="loginForm" class="" method="post">
                    <label>Email:<input type="email" id="email" name="email" required></label><br>
                    <label>Password:<input type="password" id="password" name="password" required></label><br>
                    <button type="submit">Submit</button>
                </form>
        
            </main>
            <?php include 'footer.php'; ?>
        </body>
    <script src="script.js"></script>

</html>