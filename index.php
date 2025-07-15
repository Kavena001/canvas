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

// Define banner data
$banners = [
    ['banner1.jpg', 'Professional Training', 'Enhance your team skills'],
    ['banner2.jpg', 'Certified Courses', 'Industry-recognized certifications'],
    ['banner3.jpg', 'Expert Instructors', 'Learn from the best'],
    ['banner4.jpg', 'Flexible Learning', 'Online or in-person'],
    ['banner5.jpg', 'Proven Results', 'Measurable improvements']
];
?>

<!-- Main Carousel Section -->
<div id="mainCarousel" class="carousel slide carousel-fade mb-4" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php for ($i = 0; $i < 5; $i++): ?>
        <button type="button" data-bs-target="#mainCarousel" 
                data-bs-slide-to="<?= $i ?>" 
                class="<?= $i === 0 ? 'active' : '' ?>"></button>
        <?php endfor; ?>
    </div>

    <div class="carousel-inner" style="height: 400px; background-color: #f0f0f0;">
        <?php foreach ($banners as $index => $banner):
            $imageUrl = SITE_URL . '/img/banner/' . $banner[0];
            $physicalPath = $_SERVER['DOCUMENT_ROOT'] . '/img/banner/' . $banner[0];
        ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-bs-interval="5000">
            <?php if (file_exists($physicalPath)): ?>
                <img src="<?= $imageUrl ?>" 
                     class="d-block w-100 h-100"
                     style="object-fit: cover;"
                     alt="<?= $banner[1] ?>">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 p-3 rounded">
                    <h3><?= $banner[1] ?></h3>
                    <p><?= $banner[2] ?></p>
                </div>
            <?php else: ?>
                <div class="d-block w-100 h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <i class="bi bi-image-fill" style="font-size: 3rem;"></i>
                        <p>Missing: <?= $banner[0] ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Debug Information Section -->
<div class="container mt-4">
    <div class="alert alert-secondary">
        <h4>Carousel Debug Details</h4>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Banner</th>
                    <th>URL</th>
                    <th>Exists</th>
                    <th>Preview</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($banners as $banner): 
                    $imgUrl = SITE_URL . '/img/banner/' . $banner[0];
                    $imgPath = $_SERVER['DOCUMENT_ROOT'] . '/img/banner/' . $banner[0];
                    $exists = file_exists($imgPath);
                ?>
                <tr>
                    <td><?= $banner[0] ?></td>
                    <td><small><?= $imgUrl ?></small></td>
                    <td><?= $exists ? '✅ Yes' : '❌ No' ?></td>
                    <td>
                        <?php if ($exists): ?>
                        <img src="<?= $imgUrl ?>" style="max-width: 60px;" class="img-thumbnail">
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="mb-0"><strong>Document Root:</strong> <?= $_SERVER['DOCUMENT_ROOT'] ?></p>
        <p class="mb-0"><strong>SITE_URL:</strong> <?= SITE_URL ?></p>
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