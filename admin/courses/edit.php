<?php
session_start();
require '../../includes/config.php';
require '../../includes/db.php';
require '../../includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit;
}

$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$course = $db->getRow("SELECT * FROM courses WHERE id = ?", [$courseId]);

if (!$course) {
    header('Location: list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $shortDescription = trim($_POST['short_description']);
    $duration = trim($_POST['duration']);
    $level = trim($_POST['level']);
    $language = trim($_POST['language']);
    $certificate = isset($_POST['certificate']) ? 1 : 0;
    $featured = isset($_POST['featured']) ? 1 : 0;
    
    // Handle image upload
    $image = $course['image'];
    if (!empty($_FILES['image']['name'])) {
        $upload = uploadFile($_FILES['image'], COURSE_IMAGE_PATH);
        if ($upload['success']) {
            // Delete old image if exists
            if ($image && file_exists(COURSE_IMAGE_PATH . $image)) {
                unlink(COURSE_IMAGE_PATH . $image);
            }
            $image = $upload['filename'];
        } else {
            $_SESSION['error_msg'] = $upload['error'];
        }
    }
    
    // Update course
    $sql = "UPDATE courses SET 
            title = ?, 
            description = ?, 
            short_description = ?, 
            duration = ?, 
            level = ?, 
            language = ?, 
            certificate = ?, 
            featured = ?, 
            image = ?, 
            updated_at = NOW() 
            WHERE id = ?";
            
    $params = [
        $title, 
        $description, 
        $shortDescription, 
        $duration, 
        $level, 
        $language, 
        $certificate, 
        $featured, 
        $image, 
        $courseId
    ];
    
    if ($db->update($sql, $params)) {
        $_SESSION['success_msg'] = "Cours mis à jour avec succès!";
        header("Location: list.php");
        exit;
    } else {
        $_SESSION['error_msg'] = "Erreur lors de la mise à jour du cours";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container mt-4">
        <h2 class="mb-4">Modifier le Cours</h2>
        
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error_msg'] ?></div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Titre du cours</label>
                        <input type="text" name="title" class="form-control" 
                               value="<?= htmlspecialchars($course['title']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description courte</label>
                        <textarea name="short_description" class="form-control" rows="2" required><?= 
                            htmlspecialchars($course['short_description']) ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description complète</label>
                        <textarea name="description" class="form-control" rows="5" required><?= 
                            htmlspecialchars($course['description']) ?></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Durée</label>
                            <input type="text" name="duration" class="form-control" 
                                   value="<?= htmlspecialchars($course['duration']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Niveau</label>
                            <select name="level" class="form-select" required>
                                <option value="Débutant" <?= $course['level'] === 'Débutant' ? 'selected' : '' ?>>Débutant</option>
                                <option value="Intermédiaire" <?= $course['level'] === 'Intermédiaire' ? 'selected' : '' ?>>Intermédiaire</option>
                                <option value="Avancé" <?= $course['level'] === 'Avancé' ? 'selected' : '' ?>>Avancé</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Langue</label>
                            <input type="text" name="language" class="form-control" 
                                   value="<?= htmlspecialchars($course['language']) ?>" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="certificate" 
                                       id="certificate" <?= $course['certificate'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="certificate">Certificat inclus</label>
                            </div>
                            
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="featured" 
                                       id="featured" <?= $course['featured'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="featured">Cours vedette</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Image du cours</h5>
                            
                            <?php if ($course['image']): ?>
                                <img src="<?= SITE_URL ?>/uploads/courses/<?= $course['image'] ?>" 
                                     class="img-fluid mb-3" alt="Current course image">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <input type="file" name="image" class="form-control">
                                <div class="form-text">Format JPG/PNG. Taille max: 5MB</div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>