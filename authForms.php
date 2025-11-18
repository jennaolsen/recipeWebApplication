
<form id="loginForm" class="hidden" method="post" action="login.php">
    <label>Email:<input type="email" id="loginEmail" name="email" required></label><br>
    <label>Password:<input type="password" id="loginPassword" name="password" required></label><br>
    <button type="submit">Submit</button>
</form>

<form id="signUpForm" class="hidden" method="post" action="/recipeWebApplication/registerProcess.php">
    <label>Username:<input type="text" id="username" name="username" required></label><br>
    <label>Email:<input type="email" id="singUpEmail" name="email" required></label><br>
    <label>Password:<input type="password" id="SignUpPassword" name="password" required></label><br>
    <label>Confirm Password:<input type="password" id="confirmPassword" name="confirmPassword" required></label><br>
    <button type="submit" id="submit" >Submit</button>
</form>
