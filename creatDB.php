<?php

$host = "localhost";
$user = "root";
$pass = "";

$db = "recipe_app";

$conn = new mysqli($host, $user, $pass);

if($conn->connect_error){
    die("Failed to connect to DB:".$conn->connect_error);
}

$sql_db = "CREATE DATABASE IF NOT EXISTS $db
            DEFAULT CHARACTER SET utf8mb4
            COLLATE utf8mb4_unicode_ci";

if(!$conn->query($sql_db)){
    die("Could not create DB:".$conn->error);
}

echo "<p>Database created/checked</p>";

$conn->select_db($db);

$user_table = "CREATE TABLE IF NOT EXISTS users(
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(120) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";

if(!$conn->query($user_table)){
    die("Could not create Table:".$conn->error);
}

$recipe_table = "CREATE TABLE IF NOT EXISTS recipes(
                id SERIAL PRIMARY KEY,
                spoonacular_id INT UNIQUE NOT NULL,
                title VARCHAR(255),
                image_url TEXT,
                created_at TIMESTAMP DEFAULT NOW()
                times_visited INT NOT NULL DEFAULT 0
                )";

if(!$conn->query($recipe_table)){
    die("Could not create Table:".$conn->error);
}

$saved_recipe_table = "CREATE TABLE IF NOT EXISTS saved_recipes(
                user_id INT REFERENCES users(id) ON DELETE CASCADE,
                recipe_id INT REFERENCES recipes(id) ON DELETE CASCADE,
                saved_at TIMESTAMP DEFAULT NOW(),
                PRIMARY KEY (user_id, recipe_id)
                )";

if(!$conn->query($saved_recipe_table)){
    die("Could not create Table:".$conn->error);
}

$recipe_detail_table = "CREATE TABLE IF NOT EXISTS recipeDetails(
                spoonacular_id INT PRIMARY KEY,
                title VARCHAR(255),
                summary TEXT,
                image_url TEXT,
                ready_in_minutes INT,
                instructions LONGTEXT,
                servings INT,
                ingredients LONGTEXT
                )";

if(!$conn->query($recipe_detail_table)){
    die("Could not create Table:".$conn->error);
}
echo "<p>Table created/checked</p>";

$add_comment_table = "CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if(!$conn->query($add_comment_table)){
    die("Could not create comments table:".$conn->error);
}

$sql_table_col = "ALTER TABLE users
                 ADD COLUMN is_verified TINYINT(1) NOT NULL DEFAULT 0,
                 ADD COLUMN verify_token VARCHAR(64) DEFAULT NULL";

if(!$conn->query($sql_table_col)){
    die("Could not add column:".$conn->error);
}


if(!$conn->query($add_visited_column)){
    die("Could not add column:".$conn->error);
}

echo "<p>Columns added/checked</p>";

echo "<p>Database setup complete.</p>";

$conn->close();

?>