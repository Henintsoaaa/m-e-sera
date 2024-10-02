<?php
session_start();
include '../config.php';

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
    $data = $_POST['content'];
    $id_publication = $_POST['id_publication'];
    $stmt = $pdo->prepare("INSERT INTO comments (id_compte, id_publication, contenu) VALUES (:id_compte, :id_publication, :contenu)");
    $stmt->bindParam(':id_compte', $id_compte, PDO::PARAM_INT);
    $stmt->bindParam(':id_publication', $id_publication, PDO::PARAM_INT);
    $stmt->bindParam(':contenu', $data, PDO::PARAM_STR);
    if ($stmt->execute()) {
        echo json_encode(["message" => "Publication created successfully"]);
    } else {
        error_log("Database error: " . implode(", ", $sql->errorInfo()));
        echo json_encode(["message" => "Failed to create publication"]);
    }
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
