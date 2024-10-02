<?php
require '../../config.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html'); // Redirige vers la page de connexion si non connecté
    exit();
}

$postId = $_GET['id_publication'];
try {
    $sql =$pdo->prepare("SELECT * FROM reaction_pub WHERE id_publication = ?");
    $sql->execute([$postId]);

    // Récupérer toutes les publications sous forme de tableau associatif
    $postReact = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les publications au format JSON
    echo json_encode($postReact, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // Gérer les erreurs en retournant un message JSON
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur lors de la récupération des publications : ' . $e->getMessage()
    ]);
    exit();
}
