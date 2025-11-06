document.addEventListener("DOMContentLoaded", function() {
  const loginForm = document.getElementById("loginForm");
  const signUpForm = document.getElementById("signUpForm");
  const loginBtn = document.getElementById("loginBtn");
  const signUpBtn = document.getElementById("signUpBtn");

  loginButton.addEventListener("click", () => {
    loginForm.classList.toggle("hidden");
    signUpForm.classList.add("hidden");
  });

  signUpButton.addEventListener("click", () => {
    signUpForm.classList.toggle("hidden");
    loginForm.classList.add("hidden");
  });
});
document.getElementById("signUpForm").addEventListener('submit', function(event) {
    const email = document.getElementById('signUpEmail').value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if(emailRegex.test(email) === false){
        alert("Please enter a valid email address.");
        event.preventDefault();
        return;
    }
    if(password != confirmPassword){
        alert("Passwords do not match.");
        event.preventDefault();
        return;
    }


});