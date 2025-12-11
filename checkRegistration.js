document.addEventListener("DOMContentLoaded", function () {
   
  const userInput = document.getElementById("username");
  const userEmail = document.getElementById("email");
  const passwordInput = document.getElementById("password");
  const confirmInput = document.getElementById("confirm");

  const userStatus = document.getElementById("userStatus");
  const emailStatus = document.getElementById("emailStatus");
  const passwordStatus = document.getElementById("passwordStatus");
  const comfirmStatus = document.getElementById("confirmStatus");

  



  userInput.addEventListener("input", async function () {
    const name = userInput.value.trim();

    if (name.length === 0) {
      userStatus.textContent = "";
      return;
    }

    try {
      const response = await fetch("api/checkUsername.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username: name }),
      });

      const data = await response.json();

      if (data.available) {
        
      } else {
        userStatus.textContent = "Username is taken :-(";
        userStatus.style.color = "red";
      }
    } catch (error) {
      console.error("Server error:", error);
      userStatus.textContent = "Server error.";
      userStatus.style.color = "red";
    }
  });

  userEmail.addEventListener("input", async function () {
    const email = userEmail.value.trim();

    if (email.length === 0) {
      emailStatus.textContent = "";
      return;
    }

    try {
      const response = await fetch("api/checkEmail.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email: email }),
      });

      const data = await response.json();

      if (data.available) {
        
      } else {
        emailStatus.textContent = "Email is taken :-(";
        emailStatus.style.color = "red";
      }
    } catch (error) {
      console.error("Server error:", error);
      emailStatus.textContent = "Server error.";
      emailStatus.style.color = "red";
    }
  });

 

  passwordInput.addEventListener("input", function () {
    const password = passwordInput.value;

    if (password.length === 0) {
      passwordStatus.textContent = "";
      return;
    }

    if (password.length < 8) {
      passwordStatus.textContent = "Password must be at least 8 characters long.";
      passwordStatus.style.color = "red";
    } else {
      passwordStatus.textContent = "";
    }
  });

  confirmInput.addEventListener("input", function () {
    const password = passwordInput.value;
    const confirmPassword = confirmInput.value;

    if (confirmPassword.length === 0) {
      comfirmStatus.textContent = "";
      return;
    }

    if (password !== confirmPassword) {
      comfirmStatus.textContent = "Passwords do not match.";
      comfirmStatus.style.color = "red";
    } else {
      comfirmStatus.textContent = "";
    }
  });



});
