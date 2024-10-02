<?php
require '../../config.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html'); // Redirige vers la page de connexion si non connecté
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

    if (empty($content) || $user_id <= 0) {
        echo json_encode(["message" => "Invalid input data"]);
        exit;
    }

    $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

    if ($conn === null) {
        echo json_encode(["message" => "Database connection failed"]);
        exit;
    }

    $sql = $conn->prepare("INSERT INTO publication (contenu, id_compte, date_pub) VALUES (:content, :user_id, NOW())");
    $sql->bindParam(':content', $content);
    $sql->bindParam(':user_id', $user_id);

    if ($sql->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "Publication created successfully"]);
    } else {
        http_response_code(400);
        error_log("Database error: " . implode(", ", $sql->errorInfo()));
        echo json_encode(["message" => "Failed to create publication"]);
    }
}
?>
