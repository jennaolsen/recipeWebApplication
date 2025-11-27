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
        window.location.href = "home.php";
    })
    .catch(err => console.error(err));
}

