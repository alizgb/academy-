/* ============================================
   TECH CAREER ACADEMY — DESIGN SYSTEM
   ============================================ */

@import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&family=Inter:wght@400;500;600;700&display=swap');

:root {
    /* Colors */
    --bg-primary: #0a1628;
    --bg-secondary: #0d1f3c;
    --bg-tertiary: #132844;
    --bg-elevated: #16304f;
    --accent-primary: #2563eb;
    --accent-primary-hover: #3b74f5;
    --accent-secondary: #60a5fa;
    --text-primary: #f1f5f9;
    --text-muted: #8ca3c4;
    --text-dim: #5b7291;
    --border-color: #1e3a5f;
    --success: #34d399;
    --whatsapp: #25d366;

    /* Type */
    --font-display: 'JetBrains Mono', 'Courier New', monospace;
    --font-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;

    /* Layout */
    --max-width: 1200px;
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

html { scroll-behavior: smooth; }

body {
    font-family: var(--font-body);
    background: var(--bg-primary);
    color: var(--text-primary);
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
}

a { color: inherit; text-decoration: none; }
ul { list-style: none; }
img { max-width: 100%; display: block; }
button { font-family: inherit; cursor: pointer; }

.container {
    max-width: var(--max-width);
    margin: 0 auto;
    padding: 0 24px;
}

/* ============ TYPOGRAPHY ============ */
h1, h2, h3, h4 {
    font-family: var(--font-display);
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.02em;
}

.eyebrow {
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 500;
    color: var(--accent-secondary);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.eyebrow::before {
    content: '';
    width: 8px;
    height: 8px;
    background: var(--success);
    border-radius: 50%;
    box-shadow: 0 0 8px var(--success);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}

/* ============ BUTTONS ============ */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 13px 28px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 15px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.btn-primary {
    background: var(--accent-primary);
    color: #fff;
}
.btn-primary:hover {
    background: var(--accent-primary-hover);
    transform: translateY(-1px);
}

.btn-outline {
    background: transparent;
    border-color: var(--border-color);
    color: var(--text-primary);
}
.btn-outline:hover {
    border-color: var(--accent-secondary);
    color: var(--accent-secondary);
}

.btn-block { width: 100%; }
.btn-lg { padding: 16px 32px; font-size: 16px; }

/* ============ NAVBAR ============ */
.navbar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: rgba(10, 22, 40, 0.85);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border-color);
}

.navbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    max-width: var(--max-width);
    margin: 0 auto;
    position: relative;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: var(--font-display);
    font-weight: 800;
    font-size: 20px;
}

.logo-mark {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    color: #fff;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 32px;
}

.nav-links a {
    font-size: 14px;
    font-weight: 500;
    color: var(--text-muted);
    transition: color 0.2s;
}
.nav-links a:hover { color: var(--text-primary); }

.nav-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.nav-toggle {
    display: none;
    background: none;
    border: none;
    color: var(--text-primary);
    font-size: 24px;
}

/* ============ HERO ============ */
.hero {
    padding: 80px 0 100px;
    position: relative;
    overflow: hidden;
    background:
        radial-gradient(ellipse 800px 500px at 80% -10%, rgba(37, 99, 235, 0.25), transparent),
        radial-gradient(ellipse 600px 400px at 0% 30%, rgba(96, 165, 250, 0.12), transparent);
}

.hero-inner {
    max-width: 760px;
}

.hero h1 {
    font-size: clamp(40px, 6vw, 68px);
    margin: 20px 0 24px;
}

.hero h1 .accent { color: var(--accent-secondary); }

.hero p {
    font-size: 18px;
    color: var(--text-muted);
    max-width: 560px;
    margin-bottom: 36px;
}

.hero-actions {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.terminal-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 100px;
    padding: 8px 16px;
    font-family: var(--font-display);
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 24px;
}

.hero-stats {
    display: flex;
    gap: 40px;
    margin-top: 56px;
    flex-wrap: wrap;
}

.hero-stat-num {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 800;
    color: var(--accent-secondary);
}

.hero-stat-label {
    font-size: 13px;
    color: var(--text-dim);
    margin-top: 4px;
}

/* ============ SECTIONS ============ */
.section {
    padding: 90px 0;
}

.section-header {
    max-width: 640px;
    margin-bottom: 56px;
}

.section-header h2 {
    font-size: clamp(28px, 4vw, 40px);
    margin-top: 16px;
}

.section-header p {
    color: var(--text-muted);
    font-size: 17px;
    margin-top: 16px;
}

.section-alt { background: var(--bg-secondary); }

/* ============ COURSE CATEGORY GRID ============ */
.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
}

.category-card {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 32px;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}

.category-card:hover {
    border-color: var(--accent-primary);
    transform: translateY(-4px);
}
.category-card:hover::before { transform: scaleX(1); }

.category-icon {
    width: 52px;
    height: 52px;
    border-radius: var(--radius-md);
    background: var(--bg-elevated);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-secondary);
    margin-bottom: 20px;
}

.category-card h3 {
    font-size: 20px;
    margin-bottom: 10px;
}

.category-card p {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 20px;
}

.category-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
    font-size: 13px;
    color: var(--text-dim);
}

.category-link {
    color: var(--accent-secondary);
    font-weight: 600;
    font-size: 14px;
}

/* ============ COURSE CARDS ============ */
.course-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.course-card {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
}

.course-card:hover {
    border-color: var(--accent-primary);
    transform: translateY(-4px);
}

.course-thumb {
    height: 140px;
    background: linear-gradient(135deg, var(--bg-elevated), var(--bg-secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-secondary);
    font-family: var(--font-display);
    font-size: 13px;
    border-bottom: 1px solid var(--border-color);
}

.course-card-body {
    padding: 22px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.course-tag {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--accent-secondary);
    margin-bottom: 10px;
}

.course-card h3 { font-size: 17px; margin-bottom: 8px; }
.course-card-desc { color: var(--text-muted); font-size: 13px; flex: 1; margin-bottom: 16px; }

.course-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    border-top: 1px solid var(--border-color);
    font-size: 13px;
    color: var(--text-dim);
}

/* ============ TESTIMONIALS ============ */
.testimonial-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.testimonial-card {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 28px;
}

.testimonial-quote {
    font-size: 15px;
    color: var(--text-primary);
    margin-bottom: 24px;
    line-height: 1.7;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 12px;
}

.author-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 14px;
    color: #fff;
}

.author-name { font-weight: 600; font-size: 14px; }
.author-role { font-size: 12px; color: var(--text-dim); }

/* ============ FAQ ACCORDION ============ */
.faq-list { max-width: 760px; }

.faq-item {
    border-bottom: 1px solid var(--border-color);
}

.faq-question {
    width: 100%;
    background: none;
    border: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 4px;
    font-family: var(--font-body);
    font-weight: 600;
    font-size: 16px;
    color: var(--text-primary);
    text-align: left;
}

.faq-icon {
    font-family: var(--font-display);
    font-size: 20px;
    color: var(--accent-secondary);
    transition: transform 0.2s ease;
    flex-shrink: 0;
    margin-left: 20px;
}

.faq-item.open .faq-icon { transform: rotate(45deg); }

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.25s ease;
}

.faq-item.open .faq-answer { max-height: 300px; }

.faq-answer p {
    padding: 0 4px 24px;
    color: var(--text-muted);
    font-size: 15px;
}

/* ============ CTA BANNER ============ */
.cta-banner {
    background: linear-gradient(135deg, var(--bg-secondary), var(--bg-tertiary));
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 56px;
    text-align: center;
}

.cta-banner h2 { font-size: 30px; margin-bottom: 14px; }
.cta-banner p { color: var(--text-muted); margin-bottom: 28px; }

/* ============ FOOTER ============ */
footer {
    background: var(--bg-secondary);
    border-top: 1px solid var(--border-color);
    padding: 64px 0 24px;
}

.footer-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1fr;
    gap: 40px;
    margin-bottom: 48px;
}

.footer-col h4 {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--accent-secondary);
    margin-bottom: 18px;
}

.footer-col ul li { margin-bottom: 12px; }
.footer-col ul li a {
    color: var(--text-muted);
    font-size: 14px;
    transition: color 0.2s;
}
.footer-col ul li a:hover { color: var(--text-primary); }

.footer-about p {
    color: var(--text-muted);
    font-size: 14px;
    margin-top: 16px;
    max-width: 280px;
}

.footer-bottom {
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 13px;
    color: var(--text-dim);
}

/* ============ WHATSAPP WIDGET ============ */
.whatsapp-widget {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 200;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.4);
    max-width: 260px;
    text-decoration: none;
    transition: transform 0.2s ease;
}
.whatsapp-widget:hover { transform: translateY(-2px); }

.whatsapp-icon {
    width: 40px;
    height: 40px;
    background: var(--whatsapp);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #fff;
    font-size: 20px;
}

.whatsapp-text strong { display: block; font-size: 13px; color: var(--text-primary); }
.whatsapp-text span { font-size: 12px; color: var(--text-muted); }

.whatsapp-close {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 22px;
    height: 22px;
    background: var(--bg-elevated);
    border: 1px solid var(--border-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: var(--text-muted);
}

/* ============ AUTH FORMS ============ */
.auth-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    background:
        radial-gradient(ellipse 800px 500px at 50% -10%, rgba(37, 99, 235, 0.2), transparent);
}

.auth-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 44px;
    width: 100%;
    max-width: 420px;
}

.auth-card .logo { justify-content: center; margin-bottom: 8px; }

.auth-header { text-align: center; margin-bottom: 32px; }
.auth-header h1 { font-size: 22px; margin: 12px 0 8px; }
.auth-header p { color: var(--text-muted); font-size: 14px; }

.form-group { margin-bottom: 18px; }

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-muted);
}

.form-group input, .form-group select, .form-group textarea {
    width: 100%;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    padding: 12px 14px;
    color: var(--text-primary);
    font-family: var(--font-body);
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    outline: none;
    border-color: var(--accent-primary);
}

.form-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #fca5a5;
    padding: 12px 16px;
    border-radius: var(--radius-sm);
    font-size: 14px;
    margin-bottom: 20px;
}

.form-success {
    background: rgba(52, 211, 153, 0.1);
    border: 1px solid rgba(52, 211, 153, 0.3);
    color: var(--success);
    padding: 12px 16px;
    border-radius: var(--radius-sm);
    font-size: 14px;
    margin-bottom: 20px;
}

.auth-footer {
    text-align: center;
    margin-top: 24px;
    font-size: 14px;
    color: var(--text-muted);
}
.auth-footer a { color: var(--accent-secondary); font-weight: 600; }

/* ============ DASHBOARD ============ */
.dash-layout { display: flex; min-height: 100vh; }

.dash-sidebar {
    width: 260px;
    background: var(--bg-secondary);
    border-right: 1px solid var(--border-color);
    padding: 24px;
    flex-shrink: 0;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
}

.dash-sidebar .logo { margin-bottom: 40px; }

.dash-nav a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 11px 14px;
    border-radius: var(--radius-sm);
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 4px;
    transition: all 0.2s;
}

.dash-nav a:hover, .dash-nav a.active {
    background: var(--bg-tertiary);
    color: var(--text-primary);
}
.dash-nav a.active { color: var(--accent-secondary); }

.dash-main { flex: 1; padding: 40px; max-width: 1100px; }

.dash-header { margin-bottom: 36px; }
.dash-header h1 { font-size: 26px; }
.dash-header p { color: var(--text-muted); margin-top: 6px; }

.dash-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px; }

.stat-card {
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 22px;
}
.stat-card .num { font-family: var(--font-display); font-size: 30px; font-weight: 800; color: var(--accent-secondary); }
.stat-card .label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

.session-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--bg-tertiary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 18px 22px;
    margin-bottom: 12px;
    flex-wrap: wrap;
    gap: 12px;
}

.session-row .info h4 { font-family: var(--font-body); font-size: 15px; font-weight: 600; }
.session-row .info span { font-size: 13px; color: var(--text-dim); }

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-dim);
    border: 1px dashed var(--border-color);
    border-radius: var(--radius-md);
}

/* Video player */
.video-wrapper {
    background: #000;
    border-radius: var(--radius-md);
    overflow: hidden;
    margin-bottom: 20px;
}
.video-wrapper video { width: 100%; display: block; max-height: 70vh; }

/* Tables (admin) */
.data-table { width: 100%; border-collapse: collapse; background: var(--bg-tertiary); border-radius: var(--radius-md); overflow: hidden; }
.data-table th, .data-table td {
    text-align: left;
    padding: 14px 18px;
    border-bottom: 1px solid var(--border-color);
    font-size: 14px;
}
.data-table th { color: var(--text-muted); font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; }
.data-table tr:last-child td { border-bottom: none; }

.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}
.badge-success { background: rgba(52,211,153,0.15); color: var(--success); }
.badge-muted { background: var(--bg-elevated); color: var(--text-muted); }

/* ============ RESPONSIVE ============ */
@media (max-width: 900px) {
    .nav-links {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--bg-secondary);
        border-bottom: 1px solid var(--border-color);
        flex-direction: column;
        padding: 16px 24px;
        gap: 16px;
    }
    .nav-links.mobile-open { display: flex; }
    .nav-toggle { display: block; }
    .footer-grid { grid-template-columns: 1fr 1fr; }
    .dash-sidebar { display: none; }
    .dash-main { padding: 24px; }
}

@media (max-width: 600px) {
    .footer-grid { grid-template-columns: 1fr; }
    .hero { padding: 56px 0 64px; }
    .section { padding: 60px 0; }
    .cta-banner { padding: 36px 24px; }
    .whatsapp-text { display: none; }
    .whatsapp-widget { padding: 14px; }
}

@media (prefers-reduced-motion: reduce) {
    * { animation-duration: 0.01ms !important; transition-duration: 0.01ms !important; }
}
