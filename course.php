<?php
require 'includes/config.php';
require 'includes/db.php';
require 'includes/header.php';

// Get course ID from URL
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch course details from database
$course = $db->getRow("SELECT * FROM courses WHERE id = ?", [$courseId]);

if (!$course) {
    header('Location: courses.php');
    exit;
}

// Process learning objectives (stored as JSON or comma-separated)
$objectives = [];
if (!empty($course['objectives'])) {
    $objectives = json_decode($course['objectives'], true) ?: explode("\n", $course['objectives']);
}
?>

<!-- Course Detail Header -->
<header class="py-5" style="background-color: #0056b3; color: white;">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1><?= htmlspecialchars($course['title']) ?></h1>
                <p class="lead"><?= htmlspecialchars($course['short_description']) ?></p>
            </div>
            <div class="col-md-4 text-end">
                <a href="enrollment/enroll.php?course_id=<?= $course['id'] ?>" 
                   class="btn btn-light btn-lg enroll-btn"
                   onclick="localStorage.setItem('selectedCourse', <?= $course['id'] ?>); localStorage.setItem('selectedCourseTitle', '<?= htmlspecialchars($course['title']) ?>')">
                   S'inscrire maintenant
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Course Content -->
<section class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <?php if ($course['image']): ?>
                <img src="uploads/courses/<?= htmlspecialchars($course['image']) ?>" 
                     class="img-fluid mb-4" 
                     alt="<?= htmlspecialchars($course['title']) ?>">
            <?php endif; ?>
            
            <h2>Description du cours</h2>
            <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>
            
            <?php if (!empty($objectives)): ?>
                <h3 class="mt-4">Objectifs d'apprentissage</h3>
                <ul>
                    <?php foreach ($objectives as $objective): ?>
                        <li><?= htmlspecialchars(trim($objective)) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            
            <h3 class="mt-4">Public cible</h3>
            <p><?= nl2br(htmlspecialchars($course['target_audience'] ?? 'Ce cours s\'adresse à tous les professionnels souhaitant développer leurs compétences.')) ?></p>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header" style="background-color: #65697d; color: white;">
                    <h5 class="mb-0">Détails du cours</h5>
                </div>
                <div class="card-body">
                    <p><strong>Durée:</strong> <?= htmlspecialchars($course['duration']) ?></p>
                    <p><strong>Effort:</strong> <?= htmlspecialchars($course['weekly_effort'] ?? '4-6 heures/semaine') ?></p>
                    <p><strong>Niveau:</strong> <?= htmlspecialchars($course['level']) ?></p>
                    <p><strong>Langue:</strong> <?= htmlspecialchars($course['language'] ?? 'Français') ?></p>
                    <p><strong>Certificat:</strong> <?= $course['certificate'] ? 'Oui' : 'Non' ?></p>
                    <a href="enrollment/enroll.php?course_id=<?= $course['id'] ?>" 
                       class="btn btn-primary w-100 mt-3 enroll-btn"
                       onclick="localStorage.setItem('selectedCourse', <?= $course['id'] ?>); localStorage.setItem('selectedCourseTitle', '<?= htmlspecialchars($course['title']) ?>')">
                       S'inscrire maintenant
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>

<script>
// Store course info when enroll button is clicked
document.addEventListener('DOMContentLoaded', function() {
    const enrollButtons = document.querySelectorAll('.enroll-btn');
    
    enrollButtons.forEach(button => {
        button.addEventListener('click', function() {
            localStorage.setItem('selectedCourse', <?= $course['id'] ?>);
            localStorage.setItem('selectedCourseTitle', '<?= htmlspecialchars($course['title']) ?>');
        });
    });
});
</script>