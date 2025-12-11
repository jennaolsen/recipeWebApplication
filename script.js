function incrementVisit(event, recipeId, href){
    event.preventDefault();
    fetch('incrementVisit.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'recipeId=' + encodeURIComponent(recipeId)
    }).finally(() => {
        window.location.href = href;
    });
}

function logOut(){
    fetch('logout.php', { method: 'POST'})
    .then(response => response.json())
    .then(data =>{
        console.log(data.message);
        window.location.href = "index.php";
    })
    .catch(err => console.error(err));
}

function randCook(){
    const btn = document.getElementById("randCook");
    if(btn.disabled) return;

    let wheelSpining = document.createElement("img")
    wheelSpining.src = `wheelSpin.gif?i=${0}`
    wheelSpining.classList.add("wheelSpining")
    const wheelContainer = document.getElementById("wheelContainer");
    wheelContainer.innerHTML = "";
    wheelContainer.appendChild(wheelSpining)

    
    fetch('randomRecipe.php', { method: 'GET'})
    .then(response => response.json())
    .then(data =>{
        //if(!data.recipe){
        //    alert("No recipe found, please try again later.");
        //    return;
        //}
        const recipeId = data.recipeId;
        setTimeout(() =>{
            window.location.href = "recipeDetails.php?id=" + recipeId;
        }, 1000)
        
    })
    .catch(err => console.error(err));
}

