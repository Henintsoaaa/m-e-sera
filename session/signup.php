<?php

session_start();

// Inclure le fichier de configuration pour se connecter à la base de données
include '../config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupérer les valeurs du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    try {

        // Vérifier si les données existent déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM compte WHERE email = :email ");
        $stmt->execute([
            ':email' => $email,
        ]);

        $exist = $stmt->fetchColumn();

        if ($exist) {
            echo "you have already an account.";
        } else {
            // Préparer la requête d'insertion dans la base de données
            $stmt = $pdo->prepare("INSERT INTO compte (nom, prenom, password, email) 
                                     VALUES (:nom, :prenom, :password, :email)");

            // Exécuter la requête avec les valeurs du formulaire
            $stmt->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':password' => $password,
                ':email' => $email,
            ]);

            // Récupérer l'ID de l'utilisateur nouvellement créé
            $userId = $pdo->lastInsertId();

            // Utiliser cet ID pour démarrer la session
            $_SESSION['id'] = $userId;


            // Redirection vers la page d'affichage des données après l'insertion
            header('Location: ../home.php');
            exit(); // Assurez-vous d'arrêter l'exécution du script après la redirection
        }
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    echo "Méthode non autorisée";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSCRIPTION</title>
    <link rel="stylesheet" href="./../front/inscription.css">
</head>

<body>
    <form action="" method="post">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" required>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="password">Mot de passe:</label>
        <input type="password" name="password" required>

        <button type="submit">ENREGISTRER</button>
    </form>
</body>

</html>