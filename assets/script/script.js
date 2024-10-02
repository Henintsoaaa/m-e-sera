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
      if (data.length > 0) {
        data.forEach((comment) => {
          const commentHTML = `
            <div class="comment" id="comment-${comment.id}">
                <h4>${comment.prenom}</h4>
                <p>${comment.contenu}</p>
                <div id="comment-reactions-${comment.id}">
                    <!-- Les réactions seront chargées ici -->
                </div>
                <button onclick="handleCommentReaction(${comment.id}, 'like', ${publicationId})">J'aime</button>
                <button onclick="handleCommentReaction(${comment.id}, 'dislike', ${publicationId})">Je n'aime pas</button>
              
            </div>
          `;
          commentsListDiv.innerHTML += commentHTML;
          // loadCommentReactions(comment.id); // Charger les réactions pour chaque commentaire
        });
      } else {
        commentsListDiv.classList.add("hidden");
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la récupération des commentaires :", error);
    });
}
function handleComment(element) {
  console.log(element);
  const postID = element
    .closest(".comment_section")
    .getAttribute("data-id-post");

  // event.preventDefault();
  createComment(element);
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
            <div id="reaction-pub-${post.id}">
            

            </div>

            <!-- Section des commentaires -->
            <div class="comment_section" data-post-id = "${post.id}">
                <div id="comments-list-${post.id}" class="comments">

                    <!-- Les commentaires seront chargés ici -->
                </div>

                <form class="new_comment" method="post" onsubmit="">
                  <textarea id="comment_content_${
                    post.id
                  }" class="comment_content_${
            post.id
          }" placeholder="Ajouter un commentaire..." name="content"></textarea>
                  <input type="hidden" name="post_id" value="${post.id}">
                  <button class="comment_button" type="button" onclick="handleComment(this)">Commenter</button>
                </form>

            </div>
          `;
          postList.appendChild(postElement);

          // Charger les commentaires pour chaque publication
          loadComments(post.id, data.user_id);
          loadReactPub(post.id);
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
function createComment(element) {
  let postId = element.closest(".comment_section").getAttribute("data-post-id");
  const newCommentContent = element
    .closest(".comment_section")
    .querySelector(`.comment_content_${postId}`).value;

  if (newCommentContent.trim() === "") {
    alert("Le contenu du commentaire ne peut pas être vide.");
    return;
  }

  // Envoyer le commentaire au serveur
  fetch(
    `./ajax/createComment.php?content=${encodeURIComponent(
      newCommentContent
    )}&id_publication=${postId}`,
    {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    }
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        document.getElementById(`comment_content_${postId}`).value = ""; // Vider le champ de commentaire
        loadComments(postId, data.user_id); // Recharger les commentaires
      } else {
        alert("Erreur lors de la création du commentaire: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la création du commentaire:", error);
    });
}

// Fonction pour créer une reaction de publication
function createReactPub(postId) {}

// Fonction pour lire les reactions de publication
// Fonction pour lire les reactions de publication
function loadReactPub(postId) {
  console.log("tafiditra");

  fetch(`./ajax/postReaction/read.php?id_publication=${postId}`)
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      const postReactions = document.getElementById(`reaction-pub-${postId}`);

      // Vérification si les données existent et sont valides
      if (data.length > 0) {
        // Récupérer le type de réaction (si nécessaire, ajouter une vérification ici)
        const reaction = data[0]; // On suppose qu'il y a au moins une réaction et qu'on utilise la première
        if (reaction.type === "like") {
          postReactions.innerHTML = `
          <button onclick="toggleReaction(${postId})" class="coeur-btn"><img src="./assets/img/heart-solid.svg" alt="coeur" class="coeur"></button>
          `;
        } else {
          postReactions.innerHTML = `
          <button onclick="toggleReaction(${postId})" class="ncoeur-btn"><img src="./assets/img/heart-regular.svg" alt="!coeur" class="ncoeur"></button>
          `;
        }
      } else {
        // Cas où aucune réaction n'est trouvée (par défaut, afficher le bouton 'like')
        postReactions.innerHTML = `
          <button onclick="toggleReaction(${postId})" class="ncoeur-btn"><img src="./assets/img/heart-regular.svg" alt="!coeur" class="ncoeur"></button>
        `;
      }
    })
    .catch((error) => {
      console.error("Erreur lors de la récupération des réactions:", error);
    });
}

function toggleReaction(postId) {
  const reactionImg = document
    .getElementById(`reaction-pub-${postId}`)
    .querySelector("img");

  // Détecter si l'icône actuelle est un cœur ou un avion en papier
  const isHeart = reactionImg.src.includes("/assets/img/heart-regular.svg");

  // Définir la nouvelle icône en fonction de l'état actuel
  reactionImg.src = isHeart
    ? "./assets/img/heart-regular.svg" // Icône après suppression
    : "./assets/img/heart-solid.svg"; // Icône après ajout

  // Créer les données du formulaire à envoyer au serveur
  const formData = new FormData();
  formData.append("id_publication", postId);
  formData.append("type", isHeart ? "dislike" : "like"); // Définir l'action à exécuter

  // Envoyer la requête au serveur pour gérer la réaction
  fetch(
    isHeart
      ? "./ajax/postReaction/create.php"
      : "./ajax/postReaction/delete.php",
    {
      method: "POST",
      body: formData,
    }
  )
    .then((response) => response.json())
    .then((data) => {
      console.log(data.message); // Afficher le message de réponse
    })
    .catch((error) =>
      console.error("Erreur lors de la gestion de la réaction:", error)
    );
}
