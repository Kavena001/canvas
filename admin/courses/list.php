<?php
session_start();
require '../../includes/config.php';
require '../../includes/db.php';
require '../../includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit;
}

$courses = $db->getRows("SELECT * FROM courses ORDER BY created_at DESC");

// Handle featured toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_featured'])) {
    $courseId = (int)$_POST['course_id'];
    $featured = (int)$_POST['featured'];
    
    $db->update("UPDATE courses SET featured = ? WHERE id = ?", [$featured, $courseId]);
    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gestion des Cours</h2>
            <a href="add.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Ajouter un cours
            </a>
        </div>
        
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success_msg'] ?></div>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre</th>
                            <th>Durée</th>
                            <th>Niveau</th>
                            <th>Vedette</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= $course['id'] ?></td>
                            <td><?= htmlspecialchars($course['title']) ?></td>
                            <td><?= htmlspecialchars($course['duration']) ?></td>
                            <td><?= htmlspecialchars($course['level']) ?></td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                    <input type="hidden" name="featured" value="<?= $course['featured'] ? 0 : 1 ?>">
                                    <button type="submit" name="toggle_featured" class="btn btn-sm <?= $course['featured'] ? 'btn-success' : 'btn-outline-secondary' ?>">
                                        <?= $course['featured'] ? '<i class="bi bi-star-fill"></i> Vedette' : '<i class="bi bi-star"></i> Non vedette' ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $course['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <a href="delete.php?id=<?= $course['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours?')">
                                    <i class="bi bi-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>