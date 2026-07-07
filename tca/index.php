<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

$page_title = 'Home';

// Fetch categories with course counts
try {
    $categories = $pdo->query("
        SELECT c.*, COUNT(co.id) as course_count
        FROM categories c
        LEFT JOIN courses co ON co.category_id = c.id AND co.is_published = 1
        GROUP BY c.id
        ORDER BY c.sort_order
    ")->fetchAll();

    $testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order")->fetchAll();
    $faqs = $pdo->query("SELECT * FROM faqs ORDER BY sort_order")->fetchAll();
} catch (Exception $e) {
    $categories = $testimonials = $faqs = [];
}

$category_icons = [
    'headset' => '&#9742;',
    'network' => '&#9737;',
    'monitor' => '&#9646;',
];

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <span class="terminal-badge">$ status --tech-career-academy</span>
            <span class="eyebrow">Enrolling now &middot; Live &amp; recorded IT training</span>
            <h1>From zero to <span class="accent">hired</span> in IT.</h1>
            <p>Tech Career Academy trains beginners into job-ready IT professionals through live instructor-led sessions on Microsoft Teams and a full library of recorded classes you can revisit anytime.</p>
            <div class="hero-actions">
                <a href="/register.php" class="btn btn-primary btn-lg">Enroll Now</a>
                <a href="#courses" class="btn btn-outline btn-lg">Browse Courses</a>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num">3</div>
                    <div class="hero-stat-label">Training tracks</div>
                </div>
                <div>
                    <div class="hero-stat-num">100%</div>
                    <div class="hero-stat-label">Live + recorded access</div>
                </div>
                <div>
                    <div class="hero-stat-num">0</div>
                    <div class="hero-stat-label">Experience required</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="courses">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Training tracks</span>
            <h2>Pick your path into IT.</h2>
            <p>Every track combines live Teams sessions with a growing library of recordings, taught by working IT professionals.</p>
        </div>
        <div class="category-grid">
            <?php if (empty($categories)): ?>
                <div class="empty-state">Courses will appear here once added by an admin.</div>
            <?php endif; ?>
            <?php foreach ($categories as $cat): ?>
            <div class="category-card">
                <div class="category-icon"><?= $category_icons[$cat['icon']] ?? '&#9646;' ?></div>
                <h3><?= htmlspecialchars($cat['name']) ?></h3>
                <p>
                    <?php if ($cat['slug'] === 'it-support'): ?>
                        Help desk fundamentals, troubleshooting, ticketing systems, and customer-facing support skills.
                    <?php elseif ($cat['slug'] === 'networking'): ?>
                        Advanced IT support and networking: routers, switches, protocols, and infrastructure basics.
                    <?php else: ?>
                        Confident, practical computer use for everyday work — files, browsers, office tools, and safety.
                    <?php endif; ?>
                </p>
                <div class="category-meta">
                    <span><?= (int)$cat['course_count'] ?> course<?= $cat['course_count'] == 1 ? '' : 's' ?></span>
                    <a href="/courses.php?category=<?= urlencode($cat['slug']) ?>" class="category-link">View courses &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-alt" id="testimonials">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Student outcomes</span>
            <h2>What our students say.</h2>
        </div>
        <div class="testimonial-grid">
            <?php foreach ($testimonials as $t):
                $initials = strtoupper(substr($t['student_name'], 0, 1));
            ?>
            <div class="testimonial-card">
                <p class="testimonial-quote">&ldquo;<?= htmlspecialchars($t['quote']) ?>&rdquo;</p>
                <div class="testimonial-author">
                    <div class="author-avatar"><?= $initials ?></div>
                    <div>
                        <div class="author-name"><?= htmlspecialchars($t['student_name']) ?></div>
                        <div class="author-role"><?= htmlspecialchars($t['role_text']) ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" id="faq">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">FAQ</span>
            <h2>Common questions.</h2>
        </div>
        <div class="faq-list">
            <?php foreach ($faqs as $faq): ?>
            <div class="faq-item">
                <button class="faq-question">
                    <?= htmlspecialchars($faq['question']) ?>
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <p><?= htmlspecialchars($faq['answer']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-banner">
            <h2>Ready to start your IT career?</h2>
            <p>Enrollment takes two minutes. Your first live session could be this week.</p>
            <a href="/register.php" class="btn btn-primary btn-lg">Enroll Now</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
