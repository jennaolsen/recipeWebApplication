
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
                header("Location: myProfile.php");
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
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="styles.css">
    </head>
        <body class="bg-gray-50 font-sans min-h-screen flex flex-col">
            <?php include 'header.php'; ?>
            <?php include 'authForms.php'; ?>
            <main class="flex-grow flex flex-col items-center justify-center px-4 py-12">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-teal-950 mb-4 text-center">Welcome back to The Recipe Spot!</h2>
                <p class="text-lg sm:text-xl text-gray-700 text-center max-w-2xl mb-8">Login to see your favorite recipes!</p>
                <form id="loginForm" class="bg-white p-6 rounded-xl shadow-md w-full max-w-md flex flex-col gap-4" method="post">
                    <label class="flex flex-col text-gray-700 font-medium">Email or Username:
                        <input class="mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" type="text" id="email" name="email" required></label><br>
                    <label class="flex flex-col text-gray-700 font-medium">Password:
                        <input class="mt-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" type="password" id="password" name="password" required></label><br>
                    <button class = "px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow" type="submit">Submit</button>
                </form>
        
            </main>
            <?php include 'footer.php'; ?>
        </body>
    <script src="script.js"></script>

</html>