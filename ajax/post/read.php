<?php
require '../../config.php';
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html'); // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

try {
    // Préparer la requête pour récupérer les publications et le prénom de l'utilisateur associé
    $sql = $pdo->prepare("
        SELECT publication.*, compte.prenom 
        FROM publication
        JOIN compte ON publication.id_compte = compte.id
        ORDER BY date_pub DESC
    ");
    $sql->execute();

    // Récupérer toutes les publications sous forme de tableau associatif
    $publications = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les publications au format JSON
    echo json_encode($publications, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    // Gérer les erreurs en retournant un message JSON
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur lors de la récupération des publications : ' . $e->getMessage()
    ]);
    exit();
}
?>
