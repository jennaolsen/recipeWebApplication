<?php
    require __DIR__ . "/config.php";
    require __DIR__ . '/vendor/autoload.php';
    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php"); // or dashboard.php
    exit;
    }
    $count = 0;

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']) ?? '';
        $email = trim($_POST['email']) ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        $count = 0;

    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username=? OR email=?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();


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
        if ($count > 0) {
                $errors[] = "Username or email already exists in the database";
            }

        if (!$errors) {
            
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
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Register</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="styles.css">
    </head>
        <body class="bg-gray-50 font-sans min-h-screen flex flex-col">
            <?php include 'header.php'; ?>
            <main class="flex-grow flex flex-col items-center justify-center px-4 py-12">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-teal-950 mb-4 text-center">Welcom to The Recipe Spot!</h2>
                <p class="text-lg sm:text-xl text-gray-700 text-center max-w-2xl mb-8">Create an account to save your favorite recipes!</p>
                <form id="signUpForm" class="bg-white p-6 rounded-xl shadow-md w-full max-w-md flex flex-col gap-4" method="post">
                    <label class="flex flex-col text-gray-700 font-medium">Username:
                        <input class="mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" type="text" id="username" name="username" required></label><br>
                        <span id="userStatus"></span>
                    <label class="flex flex-col text-gray-700 font-medium">Email:
                        <input class="mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" type="email" id="email" name="email" required></label><br>
                        <span id="emailStatus"></span>
                    <label class="flex flex-col text-gray-700 font-medium">Password:
                        <input class="mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" type="password" id="password" name="password" required></label><br>
                        <span id="passwordStatus"></span>
                    <label class="flex flex-col text-gray-700 font-medium">Confirm Password:
                        <input class="mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" type="password" id="confirm" name="confirm" required></label><br>
                        <span id="confirmStatus"></span>
                    <button class = "px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow" type="submit" id="submit" >Submit</button>
                </form>
        
                <?php foreach($errors as $e): ?>
                    <p style="color:red;"><?= htmlspecialchars($e) ?></p>
                <?php endforeach; ?>
            </main>
            <?php include 'footer.php'; ?>
            <script src="js/checkRegistration.js"></script>

        </body>

</html>
