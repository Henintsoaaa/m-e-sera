<?php
require '../../config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html'); // Redirige vers la page de connexion si non connecté
    exit();
}


$id_compte = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Supprimer les réactions associées à la publication
    $delete_reactions = $pdo->prepare("DELETE FROM reaction_pub WHERE id_publication = :publication_id");
    $delete_reactions->bindParam(':publication_id', $postId);
    $delete_reactions->execute();

    echo json_encode(['status' => 'success', 'message' => 'Reaction supprimé avec succès.']);
}