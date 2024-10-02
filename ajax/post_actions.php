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

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'create':
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
        break;


    case 'read':
        // Lire toutes les publications
        $stmt1 = $pdo->prepare("SELECT * FROM publication ORDER BY date_pub DESC");
        $stmt1->execute();
        $posts = $stmt1->fetchAll(PDO::FETCH_ASSOC); // Utiliser FETCH_ASSOC pour un tableau associatif

        // Lire le prénom de l'utilisateur
        $stmt2 = $pdo->prepare("SELECT prenom FROM compte WHERE id = ?");
        $stmt2->execute([$id_compte]);
        $user = $stmt2->fetch(PDO::FETCH_ASSOC); // Récupérer un seul utilisateur

        // Vérifier si le prénom a été trouvé
        $prenom = $user ? $user['prenom'] : null;
        // Combiner les résultats
        $response = [
            'status' => 'success',
            'data' => $posts,
            'user_id' => $id_compte,
            'prenom' => $prenom // Inclure le prénom dans la réponse
        ];

        // Retourner le JSON
        echo json_encode($response);
        break;


    case 'update':
        // Modifier une publication
        $postId = $_POST['id'];
        $newContent = $_POST['content']; // Utilisez 'content' pour correspondre à la requête fetch

        if (trim($newContent) === '') {
            echo json_encode(['status' => 'error', 'message' => 'Le contenu ne peut pas être vide']);
            exit();
        }

        // Remplacez $id_compte par une variable contenant l'ID de compte, si nécessaire
        $stmt = $pdo->prepare("UPDATE publication SET contenu = ? WHERE id = ? AND id_compte = ?");
        $stmt->execute([$newContent, $postId, $id_compte]);

        echo json_encode(['status' => 'success', 'message' => 'Publication mise à jour avec succès.']);
        break;


    case 'delete':
        // Supprimer une publication
        $postId = $_POST['id'];
        if ($postId <= 0) {
        echo json_encode(["message" => "Invalid publication ID"]);
        exit;
    }

    // Supprimer les réactions associées à la publication
    $delete_reactions = $conn->prepare("DELETE FROM Reaction WHERE id_publication = :publication_id");
    $delete_reactions->bindParam(':publication_id', $postId);
    $delete_reactions->execute();

    // Récupérer tous les commentaires associés à cette publication
    $get_comments = $conn->prepare("SELECT id FROM Comments WHERE id_Publication = :publication_id");
    $get_comments->bindParam(':publication_id', $postId);
    $get_comments->execute();
    $comments = $get_comments->fetchAll(PDO::FETCH_ASSOC);

    // Supprimer les réactions associées à chaque commentaire
    $delete_comment_reactions = $conn->prepare("DELETE FROM ReactionComment WHERE id_comment = :comment_id");
    foreach ($comments as $comment) {
        $delete_comment_reactions->bindParam(':comment_id', $comment['id']);
        $delete_comment_reactions->execute();
    }

    // Supprimer les commentaires associés
    $delete_comments = $conn->prepare("DELETE FROM Comments WHERE id_Publication = :publication_id");
    $delete_comments->bindParam(':publication_id', $postId);
    $delete_comments->execute();

    // Maintenant, supprimer la publication
    $delete_publication = $conn->prepare("DELETE FROM Publication WHERE id = :publication_id");
    $delete_publication->bindParam(':publication_id', $postId);

    if ($delete_publication->execute()) {
        http_response_code(200);
        echo json_encode(["message" => "Publication deleted successfully"]);
    } else {
        http_response_code(400);
        error_log("Database error: " . implode(", ", $delete_publication->errorInfo()));
        echo json_encode(["message" => "Failed to delete publication"]);
    }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Action non reconnue']);
        break;
}
