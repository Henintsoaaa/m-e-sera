<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Location: ./session/login.php');
    exit();
}

$id_compte = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="./assets/style/style.css"> <!-- Inclure votre fichier de style -->
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="left"> 
                <p class="rname">m'E-sera</p>
            </div>
            <form class="right" action="./session/logout.php">
                <button type="submit">Logout</button>
            </form>

        </div>

        <h1>Bienvenue sur la page d'accueil</h1>

        <!-- Section pour créer une nouvelle publication -->
        <!-- Formulaire pour créer une nouvelle publication -->
        <form id="new_post_form" onsubmit="createPost(event)">
            <textarea id="new_post_content" name="contenu" placeholder="Écrivez quelque chose..." required></textarea>
            <input type="hidden" name="action" value="create"> <!-- Action à définir pour le script PHP -->
            <button type="submit">Publier</button>
        </form>


        <!-- Section pour afficher les publications -->
        <div id="post_list">
            <!-- Les publications seront insérées ici via JavaScript -->
        </div>
    </div>

    <!-- Inclure le fichier JavaScript -->
    <script src="./assets/script/script.js"></script>
</body>

</html>