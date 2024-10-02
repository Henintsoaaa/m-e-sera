document.addEventListener("DOMContentLoaded", function () {
  // Charger toutes les publications au chargement de la page
  loadPosts();
});

// Fonction pour charger les commentaires
function loadComments(publicationId, user_id) {
  fetch(`./ajax/comments/read.php?publication_id=${publicationId}`)
    .then((response) => response.json())
    .then((data) => {
      const commentsListDiv = document.getElementById(
        `comments-list-${publicationId}`
      );
      commentsListDiv.innerHTML = "";
      data.forEach((comment) => {
        const commentHTML = `
          <div class="comment" id="comment-${comment.id}">
              <h4>${comment.prenom}</h4>
              <p>${comment.contenu}</p>
              <div id="comment-reactions-${comment.id}">
                  <!-- Les réactions seront chargées ici -->
              </div>
              <button onclick="handleCommentReaction(${
                comment.id
              }, 'like', ${publicationId})">J'aime</button>
              <button onclick="handleCommentReaction(${
                comment.id
              }, 'dislike', ${publicationId})">Je n'aime pas</button>
              ${
                comment.id_compte == user_id
                  ? `
              <button onclick="deleteComment(${comment.id})">Supprimer</button>
              <button onclick="editComment(${comment.id}, '${encodeURIComponent(
                      comment.contenu
                    )}')">Modifier</button>
              `
                  : ""
              }
          </div>
        `;
        commentsListDiv.innerHTML += commentHTML;
        // loadCommentReactions(comment.id); // Charger les réactions pour chaque commentaire
      });
    })
    .catch((error) => {
      console.error("Erreur lors de la récupération des commentaires :", error);
    });
}

// Fonction pour charger les publications et les commentaires
function loadPosts() {
  fetch("./ajax/post_actions.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "action=read",
  })
    .then((response) => response.json())
    .then((data) => {
      const postList = document.getElementById("post_list");
      postList.innerHTML = ""; // Vider la liste avant de la remplir

      if (data.status === "success") {
        bdMess = data.data;
        bdMess.forEach((post) => {
          const postElement = document.createElement("div");
          postElement.classList.add("post");
          postElement.innerHTML = `
            <p><strong>${data.prenom}</strong> a publié:</p>
            <p>${post.contenu}</p>
            <p><small>Publié le ${post.date_pub}</small></p>

            <!-- Si l'utilisateur est le propriétaire, afficher les boutons Modifier et Supprimer -->
            ${
              post.id_compte === data.user_id
                ? `
                <button onclick="editPost(${post.id}, '${encodeURIComponent(
                    post.contenu
                  )}')">Modifier</button>
                <button onclick="deletePost(${post.id})">Supprimer</button>
                `
                : ""
            }

            <!-- Section des commentaires -->
            <div class="comment_section">
                <div id="comments-list-${post.id}" class="comments">
                    <!-- Les commentaires seront chargés ici -->
                </div>
                
                <form class="new_comment" methode="post" action="./ajax/comments/create.php">
                  <textarea id="comment_content_${
                    post.id
                  }" placeholder="Ajouter un commentaire..." name="content"></textarea>
                  <input type="hidden" name="post_id" value="${post.id}">
                  <button class="comment_button" type="submit">Commenter</button>
                </form>
            </div>
          `;
          postList.appendChild(postElement);

          // Charger les commentaires pour chaque publication
          loadComments(post.id, data.user_id);
        });
      } else {
        postList.innerHTML = "<p>Aucune publication trouvée.</p>";
      }
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des publications:", error);
    });
}

// Fonction pour créer une nouvelle publication
function createPost(event) {
  event.preventDefault(); // Empêche le rechargement de la page
  const newPostContent = document.getElementById("new_post_content").value;
  if (newPostContent.trim() === "") {
    alert("Le contenu de la publication ne peut pas être vide.");
    return;
  }

  // Envoyer la publication au serveur
  fetch("./ajax/post_action.php?action=create", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `content=${encodeURIComponent(newPostContent)}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        document.getElementById("new_post_content").value = "";
        loadPosts();
      } else {
        alert("Erreur lors de la création de la publication: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la création de la publication:", error);
    });
}

// Modification d'une publication
function editPost(id, content) {
  const newContent = prompt("Modifier le contenu", decodeURIComponent(content));

  if (newContent !== null) {
    fetch("./ajax/post/update.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `id=${id}&content=${encodeURIComponent(newContent)}`,
    })
      .then((response) => response.json())
      .then((data) => {
        alert(data.message);
        loadPosts(); // Recharger les publications
      })
      .catch((error) => {
        console.error(
          "Erreur lors de la modification de la publication:",
          error
        );
      });
  }
}

// Fonction pour supprimer les publications
function deletePost(id) {
  if (confirm("Voulez-vous vraiment supprimer cette publication ?")) {
    fetch("./ajax/post/delete.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `id=${id}`,
    })
      .then((response) => response.json())
      .then((data) => {
        alert(data.message);
        loadPosts(); // Recharger les publications
      })
      .catch((error) => {
        console.error(
          "Erreur lors de la suppression de la publication:",
          error
        );
      });
  }
}

// Fonction pour créer des commentaires
function createComment(postId, event) {
  event.preventDefault();
  console.log("a");
  const newCommentContent = document.getElementById(
    `comment_content_${postId}`
  ).value;

  if (newCommentContent.trim() === "") {
    alert("Le contenu du commentaire ne peut pas être vide.");
    return;
  }

  // Envoyer le commentaire au serveur
  fetch("./ajax/createComment.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `content=${encodeURIComponent(
      newCommentContent
    )}&id_publication=${postId}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        document.getElementById(`comment_content_${postId}`).value = ""; // Vider le champ de commentaire
        loadComments(postId); // Recharger les commentaires
      } else {
        alert("Erreur lors de la création du commentaire: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la création du commentaire:", error);
    });
}
