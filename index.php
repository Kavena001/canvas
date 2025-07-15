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
                        <img src="<?= $imgUrl ?>" style="max 60-width: 60px;"px;" class="img-th class="img-thumbnail">
                       umbnail">
                        <?php endif; ?>
                    <?php endif; ?>
                    </td>
                </tr </td>
                </tr>
                <?php endforeach>
                <?php endforeach; ?>
            </; ?>
            </tbody>
        </tbody>
        </table>
        <p classtable>
        <p class="mb-0="mb-0"><strong>Document Root:</"><strong>Document Root:</strong> <?=strong> <?= $_SERVER['DOCUMENT_ROOT $_SERVER['DOCUMENT_ROOT'] ?></p'] ?></p>
        <p class>
        <p class="mb="mb-0"><strong-0"><strong>S>SITE_URL:</strongITE_URL:</strong> <?> <?= S= SITE_URL ?></pITE_URL ?></p>
>
    </div    </div>
</>
</div>

<!-- Featureddiv>

<!-- Featured Courses Section -->
<section Courses Section -->
<section class=" class="py-py-5 bg5 bg-light">
    <-light">
    <div classdiv class="container="container">
       ">
        <h2 class <h2 class="text="text-center mb-5-center mb-5">Featured Courses</">Featured Courses</hh2>
        <?php if (!empty($featuredCourses))2>
        <?php if (!empty($featuredCourses)) : : ?>
            <div class ?>
            <div class="row g-="row g-44">
                <?">
                <?php foreachphp foreach ($featuredCourses ($featuredCourses as $course): ?>
                as $course): ?>
                <div class=" <div class="col-md-4col-md-4">
                   ">
                    <div class=" <div class="card h-100card h-100">
                       ">
                        <?php if (! <?php if (!empty($course['empty($course['image']))image'])): ?>
                            <: ?>
                            <img src="<?=img src="<?= SITE SITE_URL ?>/uploads_URL ?>/uploads/courses/courses/<?= htmlspecialchars($course/<?= htmlspecialchars($course['image']) ?['image']) ?>" 
                                 class="card>" 
                                 class="card-img-top"-img-top" 
                                 alt="<?= 
                                 alt="<?= htmlspecial htmlspecialchars($course['chars($course['title']) ?>title']) ?>">
                       ">
                        <?php endif; <?php endif; ?>
                        <div class="card-body">
                            < ?>
                        <div class="card-body">
                            <h5 class="h5 class="card-titlecard-title"><?= html"><?= htmlspecialchars($course['titlespecialchars($course['title']) ?></h5']) ?></h5>
                            <p>
                            <p class=" class="card-text"><?card-text"><?= htmlspecialchars= htmlspecialchars($course($course['short_description'])['short_description']) ?></p>
                            ?></p>
                            <a <a href="<? href="<?= S= SITE_URL ?>/ITE_URL ?>/courses/viewcourses/view.php?id=<?.php?id=<?= $course['= $course['id'] ?>"id'] ?>" 
                               
                               class="btn btn class="btn btn-primary">
                               View Details-primary">
                               View Details
                            </a
                            </a>
                        </div>
                        </div>
                    </>
                    </div>
                </div>
                </divdiv>
               >
                <?php endforeach; ?>
            </div <?php endforeach; ?>
            </div>
        <?php>
        <?php else: ?>
            else: ?>
            <div class="alert alert <div class="alert alert-warning">No-warning">No featured courses found.</div featured courses found.</div>
        <?php endif>
        <?php endif; ?>
    </; ?>
    </div>
</section>

<!--div>
</section>

<!-- Testimonials Section Testimonials Section -->
<section class="py- -->
<section class="py-5">
    <5">
    <div class="container">
       div class="container">
        <h2 class <h2 class="text-center mb-5="text-center mb-5">What Our Clients">What Our Clients Say</h2 Say</h2>
       >
        <?php if (! <?php if (!empty($testimonials))empty($testimonials)) : : ?>
            <div class ?>
            <div class="row">
                <?php foreach ($="row">
                <?php foreach ($testimonials astestimonials as $testimonial): $testimonial): ?>
                <div class ?>
                <div class="col-md-4 mb="col-md-4 mb-4">
                   -4">
                    <div class="card h <div class="card h-100">
                       -100">
                        <div class="card-body <div class="card-body">
                           ">
                            <div <div class="mb-3 text class="mb-3 text-warning">
                               -warning">
                                <?= str_re <?= str_repeat('<peat('<i class="bii class="bi bi-star bi-star-fill"></i>',-fill"></i>', $test $testimonial['rating'])imonial['rating']) ?>
                                <?= ($ ?>
                                <?= ($testimonial['rating']testimonial['rating < 5)'] < 5) ? str_repeat ? str_repeat('<i class="bi bi('<i class="bi bi-star"></i>-star"></i>', 5 - $test', 5 - $testimonial['ratingimonial['rating']) : ''']) : '' ?>
                            </div>
                            < ?>
                            </div>
                            <p class="cardp class="card-text">-text">"<?"<?= html= htmlspecialchars($testimonialspecialchars($testimonial['content']) ?['content']) ?>"</p>
                            <>"</p>
                            <div class="ddiv class="-flex align-items-centerd-flex align-items-center mt-auto mt-auto">
                                <?php">
                                <?php if (!empty($testim if (!empty($testimonial['image']))onial['image'])): ?>
                                    <img src: ?>
                                    <img src="<?= SITE="<?= SITE_URL ?>/uploads/testimon_URL ?>/uploads/testimonials/<?=ials/<?= html htmlspecialchars($specialchars($testimtestimonial['image']) ?>"onial['image']) ? 
                                         alt="<?>" 
                                         alt="<?== html htmlspecialspecialchars($testimonial['name']) ?>" 
                                         class="roundedchars($testimonial['name']) ?>" 
                                         class="rounded-circle me-circle me--3" width="50">
                               3" width="50">
                                <?php endif; ?>
                                <?php endif; ?>
 <div>
                                                                   <div>
                                    <h6 class <h6 class="mb-0="mb-0"><?"><?= htmlspecialchars= htmlspecialchars($testimonial['name($testimonial['name']) ?></h6']) ?></h6>
                                    <small>
                                    <small class=" class="text-mutedtext-muted">
                                        <?= htmlspecialchars">
                                        <?= htmlspecialchars($testimonial($testimonial['position']) ?>,['position']) ?>, 
                                        <? 
                                        <?= htmlspecialchars($test= htmlspecialchars($testimimonial['company'])onial['company']) ?>
                                    </small>
                                ?>
                                    </small>
                                </div>
                            </div>
                            </div>
                        </div>
                        </ </div>
                    </divdiv>
                    </div>
                </div>
                </div>
               >
                <?php endforeach; <?php endforeach; ?>
            </div ?>
            </div>
        <?>
        <?php else:php else: ?>
            <div class ?>
            <div class="alert="alert alert-info">No alert-info">No testimonials available.</div testimonials available.</div>
        <?php endif>
        <?php endif; ?>
    </; ?>
    </divdiv>
</section>

<?>
</section>

<?php require_once 'php require_once 'includes/fincludes/footer.php';ooter.php'; ?>