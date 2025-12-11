

<body class="bg-gray-50 font-sans">
   <header class="header-container">
       <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-teal-950"><a href="index.php">The Recipe Spot</a></h1>
       <nav>
       <ul class="flex gap-4 md:gap-6 text-teal-950 font-semibold">
           <li class="hover:text-teal-700 transition"><a href="index.php">Home</a></li>
           <li class="hover:text-teal-700 transition"><a href="recipeIndex.php">Recipe Index</a></li>
       </ul>
       <form action="searchResults.php" method="GET" class="nav-search ">
           <input type="text" name="search" placeholder="Search recipes..." required>
           <button id="submitSearch" class="btn px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow" onclick="searchRecipes()">Search</button>
        </form>
       <div class="auth-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">
                <button onclick="logOut()" class = "px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow" type = "button" id="logoutButton"> Log Out</button>
            </a>
            <a href = "myProfile.php">
                <button class = "px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow" type = "button" id="myRecipesButton"> My Profile</button>
            </a>
        <?php else: ?>
           <a href="registerPage.php">
               <button class = "px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow" type = "button" id="signUpButton"> Sign Up</button>
           </a>
           <a href="loginPage.php">
               <button class = "px-5 py-2 rounded-full bg-pink-600 text-white hover:bg-pink-700 shadow" type = "button" id="loginButton"> Log In</button>
           </a>
        <?php endif; ?>
       </div>
   </nav>
   </header>
</body>


</html>


