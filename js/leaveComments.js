document.addEventListener("DOMContentLoaded", function() {
    loadComments(RECIPE_ID);
});

function loadComments(recipeId) {
    fetch("api/getComments.php?recipe_id=" + recipeId)
        .then(response => response.json())
        .then(data => {
            const list = document.getElementById("comments-list");
            list.innerHTML = "";

            if (data.length === 0) {
                list.innerHTML = "<p>No comments yet.</p>";
                return;
            }

            data.forEach(c => {
                list.innerHTML += `
                    <div class="comment p-4 border-b border-gray-300">
                        <strong>${c.username}</strong>
                        <span class="text-gray-500 text-sm"> (${c.created_at})</span>
                        <p>${c.comment}</p>
                    </div>
                `;
            });
        });
}

function submitComment(recipeId) {
    const text = document.getElementById('commentInput').value;

    fetch('api/comments.php', {
        method: 'POST',
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            recipe_id: recipeId,
            comment_text: text
        })
    })
    .then(res => res.json())
    .then(() => {
        document.getElementById('commentInput').value = "";
        loadComments(recipeId);
    });
}

