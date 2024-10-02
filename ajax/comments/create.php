<?php
require '../../config.php';
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../../index.html'); // Redirige vers la page de connexion si non connecté
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $publicationId = (int)$_POST['post_id'];
    // $userId = (int)$_POST['user_id'];
    $commentContent = $_POST['content'];

    if (!empty($commentContent)) {
        $sql = $pdo->prepare("INSERT INTO comments (id_publication, id_compte, contenu, date) VALUES (:pub_id, :user_id, :content, NOW())");
        $sql->bindParam(':pub_id', $publicationId);
        $sql->bindParam(':user_id', $_SESSION['id']);
        $sql->bindParam(':content', $commentContent);
        $sql->execute();

        //     if ($sql->execute()) 
        //     {
        //         echo json_encode(["message" => "Comment created successfully"]);
        //     } 
        //     else 
        //     {
        //         echo json_encode(["message" => "Failed to create comment"]);
        //     }
        // } else {
        //     echo json_encode(["message" => "Comment content cannot be empty"]);
        // }
    }
}
