<?php

// Démarrer la session
session_start();

include "../config.php";
// Simuler une vérification des informations d'identification
// Remplace ceci par une vérification réelle avec une base de données
try {
    // Récupérer les données du formulaire
    $username = $_POST['prenom'];
    $pass = $_POST['password'];

    // Préparer la requête SQL en spécifiant la table
    $compte = $pdo->prepare("SELECT * FROM compte WHERE prenom = ? AND password = ?");
    $compte->execute([$username, $pass]);
    $result = $compte->fetch();



    // Vérifier les informations d'identification
    if ($compte->rowCount() > 0) {
        $_SESSION['id'] = $result['id'];
        // Rediriger vers une autre page (par exemple, dashboard.php)
        header('Location: ../home.php');
        exit(); // Important pour arrêter l'exécution du script après la redirection
    } else {
        // Rediriger vers une page d'erreur ou de connexion avec un message d'erreur
        header('Location: ../index.html?');
        exit();
    }
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
}
