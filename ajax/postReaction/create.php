<?php
require '../../config.php';
session_start();

header('Content-Type: application/json');
if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html'); // Redirige vers la page de connexion si non connecté
    exit();
}

$id_publication = $_POST['id_publication'];
$id_compte = $_SESSION['id'];
$type = $_POST['type'];

// Check if the user already reacted
$sql = $pdo->prepare("SELECT * FROM reaction_pub WHERE id_publication = :id_publication AND id_compte = :id_compte");
$sql->bindParam(':id_publication', $id_publication);
$sql->bindParam(':id_compte', $id_compte);
$sql->execute();
$existingReaction = $sql->fetch(PDO::FETCH_OBJ);

if ($existingReaction) {
    // delete reaction if it exists 
    $delete = $pdo->prepare("DELETE FROM reaction_pub WHERE id = :id");
    $delete->bindParam(':id', $existingReaction->id);
    $delete->execute();
} else {
    // Insert new reaction
    $sql = $pdo->prepare("INSERT INTO reaction_pub  (id_publication, id_compte, type) VALUES (:id_publication, :id_compte, :type)");
    $sql->bindParam(':id_publication', $id_publication);
    $sql->bindParam(':id_compte', $id_compte);
    $sql->bindParam(':type', $type);
}

if ($sql->execute()) {
    echo json_encode(['message' => 'Reaction saved successfully']);
} else {
    echo json_encode(['message' => 'Failed to save reaction']);
}
