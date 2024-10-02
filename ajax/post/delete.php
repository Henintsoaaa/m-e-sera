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
    // Supprimer les réactions associées à la publication
    $delete_reactions = $pdo->prepare("DELETE FROM reaction_pub WHERE id_publication = :publication_id");
    $delete_reactions->bindParam(':publication_id', $postId);
    $delete_reactions->execute();

    // Récupérer tous les commentaires associés à cette publication
    $get_comments = $pdo->prepare("SELECT id FROM comments WHERE id_publication = :publication_id");
    $get_comments->bindParam(':publication_id', $postId);
    $get_comments->execute();
    $comments = $get_comments->fetchAll(PDO::FETCH_ASSOC);

    // Supprimer les réactions associées à chaque commentaire
    $delete_comment_reactions = $pdo->prepare("DELETE FROM reaction_comment WHERE id_comment = :comment_id");
    foreach ($comments as $comment) {
        $delete_comment_reactions->bindParam(':comment_id', $comment['id']);
        $delete_comment_reactions->execute();
    }

    // Supprimer les commentaires associés
    $delete_comments = $pdo->prepare("DELETE FROM comments WHERE id_publication = :publication_id");
    $delete_comments->bindParam(':publication_id', $postId);
    $delete_comments->execute();

    // Maintenant, supprimer la publication
    $delete_publication = $pdo->prepare("DELETE FROM publication WHERE id = :publication_id");
    $delete_publication->bindParam(':publication_id', $postId);
    $delete_publication->execute();

    echo json_encode(['status' => 'success', 'message' => 'Publication supprimé avec succès.']);
}
