<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: home.php"); // or dashboard.php
    exit;
}
    
    require __DIR__ . "/config.php";
    require __DIR__ . '/vendor/autoload.php';


    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']) ?? '';
        $email = trim($_POST['email']) ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';


        if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
            $errors[] = "Username must be between 3 to 30 characters";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid form of email";
        }

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }

        if ($password !== $confirm) {
           $errors[] = "Passwords do not match";
        }

        if (!$errors) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username=? OR email=?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            if ($count > 0) {
                $errors[] = "Username or email already exists in the database";
            }
            else{
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $password_hash);
                if ($stmt->execute()) {
                    $stmt->close();
                    header("Location: loginPage.php?registered=1");
                    exit;
                } else {
                    $errors[] = "Failed to create user: " . $stmt->error;
                    $stmt->close();
                }
            }
        }
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
                <form id="signUpForm" method="post">
                    <label>Username:<input type="text" id="username" name="username" required></label><br>
                    <label>Email:<input type="email" id="email" name="email" required></label><br>
                    <label>Password:<input type="password" id="password" name="password" required></label><br>
                    <label>Confirm Password:<input type="password" id="confirm" name="confirm" required></label><br>
                    <button type="submit" id="submit" >Submit</button>
                </form>
        
                <?php foreach($errors as $e): ?>
                    <p style="color:red;"><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </main>
            <?php include 'footer.php'; ?>
        </body>
    <script src="script.js"></script>

</html>
