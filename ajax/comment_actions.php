<?php
session_start();
include '../config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_compte'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit();
}

$id_compte = $_SESSION['id_compte'];

$action = $_GET['action'] ?? '';

switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = $_POST['content'];
                $stmt = $pdo->prepare("INSERT INTO comments (id_compte, id_publication, contenu) VALUES (:id_compte, :id_publication, :contenu)");
                $stmt->bindParam(':id_compte', $id_compte, PDO::PARAM_INT);
                $stmt->bindParam(':id_publication', $id_publication, PDO::PARAM_INT);
                $stmt->bindParam(':contenu', $data, PDO::PARAM_STR);
                $stmt->execute();
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        break;

    case 'read':
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $publicationId = (int)$_GET['publication_id'];
            $sql = $pdo->prepare("SELECT comments.*, compte.prenom FROM comments JOIN compte ON comments.id_compte = compte.id WHERE comments.id_publication = :id_pub ORDER BY comments.date ASC");
            $sql->bindParam(':id_pub ', $publicationId);
            $sql->execute();
            $comments = $sql->fetchAll(PDO::FETCH_OBJ);
        
            echo json_encode($comments);
        }
        break;



    case 'update':
        // Modifier un commentaire
        $comment_id = $_POST['id'] ?? 0;
        $newContent = $_POST['contenu'] ?? '';

        if (trim($newContent) === '') {
            echo json_encode(['status' => 'error', 'message' => 'Le contenu ne peut pas être vide']);
            exit();
        }

        // Mise à jour du commentaire dans la base de données
        $stmt = $db->prepare("UPDATE comments SET contenu = ? WHERE id = ? AND id_compte = ?");
        $stmt->execute([$newContent, $comment_id, $id_compte]);

        echo json_encode(['status' => 'success']);
        break;

    case 'delete':
        // Supprimer un commentaire
        $comment_id = $_POST['id'] ?? 0;

        // Suppression du commentaire dans la base de données
        $stmt = $db->prepare("DELETE FROM comments WHERE id = ? AND id_compte = ?");
        $stmt->execute([$comment_id, $id_compte]);

        echo json_encode(['status' => 'success']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Action non reconnue']);
        break;
}
?>
<script>
    const user_id = <?php echo $_SESSION['id']; ?>;  // Assumez que $_SESSION['id'] contient l'ID de l'utilisateur connecté
</script>

