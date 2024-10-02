<?php
require '../../config.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html'); // Redirige vers la page de connexion si non connecté
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $publicationId = (int)$_GET['publication_id'];
    $sql = $pdo->prepare("SELECT comments.*, compte.prenom FROM comments JOIN compte ON comments.id_compte = compte.id WHERE comments.id_publication = :id_pub ORDER BY comments.date_coms ASC");
    $sql->bindParam(':id_pub', $publicationId);
    $sql->execute();
    $comments = $sql->fetchAll(PDO::FETCH_OBJ);

    echo json_encode($comments);
}
