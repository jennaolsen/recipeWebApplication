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
    let wheelSpining = document.createElement("img")
    wheelSpining.classList.add("fireworks")
    wheelSpining.classList.add("fireworks-left")
    wheelSpining.src = `wheelSpin.gif?i=${0}`

    document.body.appendChild(wheelSpining)

    setTimeout(() => {
        document.body.removeChild(wheelSpining)
    }, 3000);
    fetch('randomRecipe.php', { method: 'GET'})
    .then(response => response.json())
    .then(data =>{
        const recipeId = data.recipeId;
        window.location.href = "recipe.php?id=" + recipeId;
    })
    .catch(err => console.error(err));
}

