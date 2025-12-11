document.addEventListener("DOMContentLoaded", function(){

    const userInput = document.getElementById("username");
    const jsonForm = document.getElementById("signUpForm");
    const result = document.getElementById("output");
    
    jsonForm.addEventListener("submit", async function (e) {

        e.preventDefault();

        const name = userInput.value.trim();

        try {
            
            const response = await fetch("process_json.php", {
                method:"POST",
                headers:{"Content-Type" :"application/json"},
                body: JSON.stringify({username:name})
            });

            const data = await response.json();

            result.innerHTML = data.message;

        } catch (error) {
            console.error("Server error:", error);
            result.innerHTML = "Server error.";
        }
        
    })
    
});