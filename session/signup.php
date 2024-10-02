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
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <div class="body-container">
        <form action="" method="post" class="form-signup">
            <div class="form-group">
                <label for="nom" class="form-label">Nom:</label>
                <input type="text" name="nom" class="form-input" required>
            </div>
    
            <div class="form-group">
                <label for="prenom" class="form-label">Prénom:</label>
                <input type="text" name="prenom" class="form-input" required>
            </div>
    
            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-input" required>
            </div>
    
            <div class="form-group">
                <label for="password" class="form-label">Mot de passe:</label>
                <input type="password" name="password" class="form-input" required>
            </div>
    
            <button type="submit" class="form-button">ENREGISTRER</button>
        </form>
    </div>
</body>

</html>
