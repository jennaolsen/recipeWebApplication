

<body>
    <header>
        <h1>Our Recipe Web Application</h1>
        <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="aboutTheSite.php">About the Site</a></li>
            <li><a href="recipeIndex.php">Recipe Index</a></li>
        </ul>
        <div class="nav-search">
            <input type="text" placeholder="Search recipes...">
            <button id=searchButton class="btn btn-outline" onclick="searchRecipes()">Search</button>
        </div>
        <div class="auth-actions">
            <a href="registerPage.php">
                <button type = "button" id="signUpButton"> Sign Up</button>
            </a>
            <a href="loginPage.php">
                <button type = "button" id="loginButton"> Log In</button>
            </a>
        </div>
    </nav>
    </header>
</body>

</html>