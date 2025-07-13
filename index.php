<?php
// Enable full error reporting at the very top
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/header.php';

try {
    // Get featured courses
    $featuredCourses = $db->getRows("SELECT * FROM courses WHERE featured = 1 ORDER BY title LIMIT 3");
    
    // Get testimonials
    $testimonials = $db->getRows("SELECT * FROM testimonials WHERE featured = 1 ORDER BY RAND() LIMIT 3");
    
    // Get team members
    $teamMembers = $db->getRows("SELECT * FROM team_members ORDER BY name LIMIT 4");
} catch (Exception $e) {
    die("<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>");
}
?>

<!---------------------------------- Banner Carousel -------------------------------------------->
<!-- Updated Carousel Section in index.php -->
<div id="bannerCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <!-- Indicators -->
    <div class="carousel-indicators">
        <?php for ($i = 0; $i < 5; $i++): ?>
            <button type="button" data-bs-target="#bannerCarousel" 
                    data-bs-slide-to="<?= $i ?>" 
                    class="<?= $i === 0 ? 'active' : '' ?>"></button>
        <?php endfor; ?>
    </div>

    <div class="carousel-inner" style="height: 266px;"> <!-- Reduced height (33% less than 400px) -->
        <!-- Slide 1 -->
        <div class="carousel-item active" data-bs-interval="5000">
            <img src="<?= SITE_URL ?>/img/banner/banner1.jpg" class="d-block w-100 h-100" 
                 style="object-fit: cover;" alt="Formation professionnelle">
            <div class="carousel-caption">
                <h2 class="h4">Développez les compétences de votre équipe</h2>
                <p class="d-none d-md-block">Formations professionnelles adaptées aux besoins de votre entreprise</p>
            </div>
        </div>
        
        <!-- Slides 2-5 would follow the same pattern -->
    </div>

    <!-- Navigation Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Suivant</span>
    </button>
</div>

<!-- Featured Courses -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Cours en vedette</h2>
        <?php if (!empty($featuredCourses)) : ?>
            <div class="row g-4">
                <?php foreach ($featuredCourses as $course): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <?php if (!empty($course['image'])): ?>
                            <img src="<?= SITE_URL ?>/uploads/courses/<?= htmlspecialchars($course['image']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($course['title']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($course['short_description']) ?></p>
                            <a href="<?= SITE_URL ?>/courses/course<?= $course['id'] ?>.php" 
                               class="btn btn-primary">
                               En savoir plus
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Aucun cours vedette trouvé.</div>
        <?php endif; ?>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Ce que disent nos clients</h2>
        <?php if (!empty($testimonials)) : ?>
            <div class="row">
                <?php foreach ($testimonials as $testimonial): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="mb-3 text-warning">
                                <?= str_repeat('<i class="bi bi-star-fill"></i>', $testimonial['rating']) ?>
                                <?= ($testimonial['rating'] < 5) ? str_repeat('<i class="bi bi-star"></i>', 5 - $testimonial['rating']) : '' ?>
                            </div>
                            <p class="card-text">"<?= htmlspecialchars($testimonial['content']) ?>"</p>
                            <div class="d-flex align-items-center mt-auto">
                                <?php if (!empty($testimonial['image'])): ?>
                                    <img src="<?= SITE_URL ?>/uploads/testimonials/<?= htmlspecialchars($testimonial['image']) ?>" 
                                         alt="<?= htmlspecialchars($testimonial['name']) ?>" 
                                         class="rounded-circle me-3" width="50">
                                <?php endif; ?>
                                <div>
                                    <h6 class="mb-0"><?= htmlspecialchars($testimonial['name']) ?></h6>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($testimonial['position']) ?>, 
                                        <?= htmlspecialchars($testimonial['company']) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Aucun témoignage disponible.</div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>