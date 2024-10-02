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
    $stmt = $pdo->prepare("INSERT INTO publication (id_compte, contenu) VALUES (:id_compte, :contenu)");
    $stmt->bindParam(':id_compte', $id_compte, PDO::PARAM_INT);
    $stmt->bindParam(':contenu', $data, PDO::PARAM_STR);
    $stmt->execute();
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}