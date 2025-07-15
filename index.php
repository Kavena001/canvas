<?php
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

// Define the 3 banner data
$banners = [
    ['banner1.jpg', 'Professional Training', 'Enhance your team skills'],
    ['banner2.jpg', 'Certified Courses', 'Industry-recognized certifications'],
    ['banner3.jpg', 'Expert Instructors', 'Learn from the best']
];
?>

<!-- Main Carousel - Simplified for 3 Banners -->
<div id="mainCarousel" class="carousel slide carousel-fade mb-4" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner" style="height: 400px;">
        <?php foreach ($banners as $index => $banner): 
            $imageUrl = SITE_URL . '/img/banner/' . $banner[0];
            $physicalPath = $_SERVER['DOCUMENT_ROOT'] . '/img/banner/' . $banner[0];
        ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-bs-interval="5000">
            <img src="<?= $imageUrl ?>" 
                 class="d-block w-100 h-100"
                 style="object-fit: cover;"
                 alt="<?= $banner[1] ?>"
                 onerror="this.onerror=null; this.src='https://via.placeholder.com/1920x600/6c757d/ffffff?text=Banner+<?= $index+1 ?>'">
            
            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                <h3><?= $banner[1] ?></h3>
                <p><?= $banner[2] ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Debug Information -->
<div class="container mt-3 mb-4">
    <div class="alert alert-info">
        <h5>Carousel Verification</h5>
        <p>Showing 3 banners located at: <code><?= SITE_URL ?>/img/banner/</code></p>
        <div class="row">
            <?php foreach ($banners as $index => $banner): 
                $imgPath = $_SERVER['DOCUMENT_ROOT'] . '/img/banner/' . $banner[0];
                $exists = file_exists($imgPath);
            ?>
            <div class="col-md-4 text-center">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6><?= $banner[0] ?></h6>
                        <p class="mb-2"><?= $exists ? '✅ Found' : '❌ Missing' ?></p>
                        <?php if ($exists): ?>
                        <img src="<?= SITE_URL ?>/img/banner/<?= $banner[0] ?>" 
                             style="max-height: 80px; width: auto;" 
                             class="img-fluid rounded">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Featured Courses Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Featured Courses</h2>
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
                            <a href="<?= SITE_URL ?>/courses/view.php?id=<?= $course['id'] ?>" 
                               class="btn btn-primary">
                               View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">No featured courses found.</div>
        <?php endif; ?>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">What Our Clients Say</h2>
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
            <div class="alert alert-info">No testimonials available.</div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>