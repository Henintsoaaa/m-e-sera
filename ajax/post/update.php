<?php
session_start();
include './../../config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit();
}

header('Content-Type: application/json');

$id_compte = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Modifier une publication
    $postId = $_POST['id'];
    $newContent = $_POST['content']; // Utilisez 'content' pour correspondre à la requête fetch

    if (trim($newContent) === '') {
        echo json_encode(['status' => 'error', 'message' => 'Le contenu ne peut pas être vide']);
        exit();
    }

    // Remplacez $id_compte par une variable contenant l'ID de compte, si nécessaire
    $stmt = "UPDATE publication SET contenu = '$newContent' WHERE id = $postId AND id_compte = $id_compte";
    $pdo->exec($stmt);

    echo json_encode(['status' => 'success', 'message' => 'Publication mise à jour avec succès.']);
}
