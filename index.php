<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

$page_title = 'Home';

try {
    // Flagship course: the IT Support track is the academy's primary product.
    $stmt = $pdo->prepare("
        SELECT co.*, cat.name AS category_name, cat.slug AS category_slug
        FROM courses co
        JOIN categories cat ON cat.id = co.category_id
        WHERE cat.slug = 'it-support' AND co.is_published = 1
        ORDER BY co.created_at ASC
        LIMIT 1
    ");
    $stmt->execute();
    $flagship = $stmt->fetch();

    $flagship_recordings = [];
    if ($flagship) {
        $stmt = $pdo->prepare("SELECT title FROM recordings WHERE course_id = ? ORDER BY uploaded_at ASC LIMIT 6");
        $stmt->execute([$flagship['id']]);
        $flagship_recordings = $stmt->fetchAll();
    }

    $testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY sort_order")->fetchAll();
    $faqs = $pdo->query("SELECT * FROM faqs ORDER BY sort_order")->fetchAll();
} catch (Exception $e) {
    $flagship = null;
    $flagship_recordings = [];
    $testimonials = $faqs = [];
}

$flagship_url = $flagship ? '/course.php?slug=' . urlencode($flagship['slug']) : '/courses.php';

// Simple inline checkmark, reused across list items below.
function tca_check() {
    return '<svg class="check" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16.5 5.5L8 14 3.5 9.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}

// A real asset when we have one, otherwise a subtle (not empty-feeling) placeholder.
function tca_asset_slot($label, $src = null, $alt = '') {
    if ($src) {
        return '<div class="asset-slot"><img src="' . htmlspecialchars($src) . '" alt="' . htmlspecialchars($alt) . '"></div>';
    }
    $icon = '<svg class="slot-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><circle cx="9" cy="10.5" r="1.6" stroke="currentColor" stroke-width="1.6"/><path d="M21 15l-5.5-4.5a1.5 1.5 0 00-2 .1L7 16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>';
    return '<div class="asset-slot">' . $icon . '<span class="label">' . htmlspecialchars($label) . '</span></div>';
}

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <h1>From zero to <span class="accent">hired</span> in IT.</h1>
            <p class="hero-lead">Tech Career Academy trains complete beginners into job-ready IT Support professionals — through live instructor-led sessions on Microsoft Teams and a full library of recordings you can revisit anytime.</p>
            <div class="hero-actions">
                <a href="<?= htmlspecialchars($flagship_url) ?>" class="btn btn-primary btn-lg">Explore the IT Support Course</a>
                <a href="#proof" class="btn btn-ghost btn-lg">See real course material &darr;</a>
            </div>
            <p class="hero-meta">
                <?= $flagship && $flagship['duration_weeks'] ? (int)$flagship['duration_weeks'] . ' Weeks' : '6 Weeks' ?>
                &middot; Live + Recorded &middot; Certificate Included
            </p>
        </div>
        <div class="hero-visual">
            <?= tca_asset_slot('Real class or instructor photo — asset needed', $flagship['thumbnail'] ?? null, 'IT Support course') ?>
        </div>
    </div>
</section>

<section class="section" id="transformation">
    <div class="container">
        <div class="section-header">
            <h2>Start with no IT experience. Leave with practical IT Support skills.</h2>
            <p>No lectures. No theory dumps. Every session is built around the actual work a help desk or support technician does on the job.</p>
        </div>
        <ul class="outcome-grid">
            <li class="outcome-item"><?= tca_check() ?><span>Troubleshoot real computer problems</span></li>
            <li class="outcome-item"><?= tca_check() ?><span>Support users professionally</span></li>
            <li class="outcome-item"><?= tca_check() ?><span>Work with ticketing systems</span></li>
            <li class="outcome-item"><?= tca_check() ?><span>Manage Windows environments</span></li>
            <li class="outcome-item"><?= tca_check() ?><span>Understand networking fundamentals</span></li>
        </ul>
    </div>
</section>

<section class="section section-alt" id="course">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Flagship program</span>
            <h2>IT Support Career</h2>
        </div>

        <?php if ($flagship): ?>
        <div class="flagship">
            <div class="flagship-body">
                <span class="flagship-badge">Flagship Program</span>
                <h3><?= htmlspecialchars($flagship['title']) ?></h3>
                <p class="desc"><?= htmlspecialchars($flagship['short_description'] ?: $flagship['description']) ?></p>

                <div class="flagship-meta">
                    <div><strong>Level</strong><?= htmlspecialchars(ucfirst($flagship['level'])) ?></div>
                    <div><strong>Duration</strong><?= $flagship['duration_weeks'] ? (int)$flagship['duration_weeks'] . ' weeks' : 'Self-paced' ?></div>
                    <div><strong>Format</strong>Live + recorded</div>
                    <?php if ($flagship['teacher_name']): ?>
                    <div><strong>Instructor</strong><?= htmlspecialchars($flagship['teacher_name']) ?></div>
                    <?php endif; ?>
                </div>

                <ul class="flagship-includes">
                    <li><?= tca_check() ?> Live sessions on Microsoft Teams, plus a full recorded library</li>
                    <li><?= tca_check() ?> Real help desk scenarios — support tickets, troubleshooting, customer conversations</li>
                    <li><?= tca_check() ?> Certificate of completion</li>
                </ul>

                <a href="<?= htmlspecialchars($flagship_url) ?>" class="btn btn-primary btn-lg">View full course details</a>
            </div>
            <div class="flagship-side">
                <h4>What's inside</h4>
                <ul>
                    <?php if (!empty($flagship_recordings)): ?>
                        <?php foreach ($flagship_recordings as $r): ?>
                            <li><?= htmlspecialchars($r['title']) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Full syllabus published on the course page</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <?php else: ?>
            <div class="empty-state">Course details will appear here once the IT Support course is published.</div>
        <?php endif; ?>
    </div>
</section>

<section class="section" id="proof">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Practical proof</span>
            <h2>What the training actually looks like.</h2>
            <p>Real material from the course — not stock photography.</p>
        </div>
        <div class="proof-layout">
            <div class="proof-visual">
                <?= tca_asset_slot('Real lesson or screenshot — asset needed', $flagship['thumbnail'] ?? null, 'IT Support course material') ?>
            </div>
            <ul class="proof-points">
                <li><?= tca_check() ?><span>Real recorded lessons from the actual course library</span></li>
                <li><?= tca_check() ?><span>Real support-ticket exercises, not simulations</span></li>
                <li><?= tca_check() ?><span>Live Teams sessions with the instructor</span></li>
                <li><?= tca_check() ?><span>Your own student dashboard from day one</span></li>
            </ul>
        </div>
    </div>
</section>

<section class="section section-alt" id="instructor">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Instructor</span>
            <h2>Taught by someone who has worked the job.</h2>
        </div>
        <div class="instructor">
            <?= tca_asset_slot('Instructor photo — asset needed') ?>
            <div>
                <h3><?= htmlspecialchars($flagship['teacher_name'] ?? 'Instructor name pending') ?></h3>
                <div class="role">IT Support Instructor</div>
                <p class="bio">Instructor bio &mdash; content needed. (Real background, companies worked at, and years of experience should replace this placeholder before launch.)</p>
            </div>
        </div>
    </div>
</section>

<section class="section" id="testimonials">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow">Student outcomes</span>
            <h2>What our students say.</h2>
        </div>
        <div class="testimonial-grid">
            <?php foreach ($testimonials as $t):
                $initials = strtoupper(substr($t['student_name'], 0, 1));
                // Hiring-outcome testimonials are the highest-value proof — give them visual priority.
                $is_hire_proof = (stripos($t['quote'], 'job') !== false) || (stripos($t['role_text'], ' at ') !== false);
            ?>
            <div class="testimonial-card<?= $is_hire_proof ? ' featured' : '' ?>">
                <?php if ($is_hire_proof): ?><span class="testimonial-badge">Real hiring outcome</span><?php endif; ?>
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

<section class="section section-alt" id="certificate">
    <div class="container">
        <div class="certificate-block">
            <?= tca_asset_slot('Certificate preview — asset needed') ?>
            <div>
                <span class="eyebrow">Certificate</span>
                <h2>Finish the course, get a certificate that says so.</h2>
                <p>Students who complete all sessions in the IT Support course receive a certificate of completion &mdash; something real to add to a resume or LinkedIn profile.</p>
            </div>
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
                    <svg class="faq-icon" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M10 4v12M4 10h12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </button>
                <div class="faq-answer"><div>
                    <p><?= htmlspecialchars($faq['answer']) ?></p>
                </div></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="cta-banner">
            <h2>Ready to become job-ready in IT?</h2>
            <p>See the full curriculum, pricing, and what happens right after you enroll.</p>
            <a href="<?= htmlspecialchars($flagship_url) ?>" class="btn btn-primary btn-lg">View the IT Support Course</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
