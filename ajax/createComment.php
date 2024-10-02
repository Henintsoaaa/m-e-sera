<?php
session_start();
include '../config.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit();
}

header('Content-Type: application/json');

$id_compte = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $data = $_GET['content'];
    $id_publication = $_GET['id_publication'];

    $stmt = $pdo->prepare("INSERT INTO comments (id_compte, id_publication, contenu) VALUES (:id_compte, :id_publication, :contenu)");
    $stmt->bindParam(':id_compte', $id_compte, PDO::PARAM_INT);
    $stmt->bindParam(':id_publication', $id_publication, PDO::PARAM_INT);
    $stmt->bindParam(':contenu', $data, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Publication created successfully', 'user_id' => $id_compte]);
    } else {
        error_log("Database error: " . implode(", ", $stmt->errorInfo()));
        echo json_encode(['status' => 'error', 'message' => 'Failed to create publication']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
