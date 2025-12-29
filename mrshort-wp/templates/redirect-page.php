<?php
/**
 * MrShort Redirect Page Template
 * Features: Multi-theme, A/B Testing, SEO, Multi-Ad Network Fallback
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme settings
$theme = get_option('mrshort_theme', 'light');

// A/B Testing
$ab_enabled = get_option('mrshort_ab_enabled', false);
$ab_wait_times = [5, 7, 10];
$actual_wait_time = $wait_time;
if ($ab_enabled) {
    $actual_wait_time = $ab_wait_times[array_rand($ab_wait_times)];
    // Track A/B impression
    $ab_stats = get_option('mrshort_ab_stats', []);
    $variant_key = 'wait_' . $actual_wait_time;
    if (!isset($ab_stats[$variant_key])) {
        $ab_stats[$variant_key] = ['impressions' => 0, 'clicks' => 0];
    }
    $ab_stats[$variant_key]['impressions']++;
    update_option('mrshort_ab_stats', $ab_stats);
}

// Ad settings
$adsense_header = get_option('mrshort_adsense_header', '');
$adsense_footer = get_option('mrshort_adsense_footer', '');
$adsense_sidebar = get_option('mrshort_adsense_sidebar', '');
$medianet_header = get_option('mrshort_medianet_header', '');
$medianet_footer = get_option('mrshort_medianet_footer', '');
$propeller_header = get_option('mrshort_propeller_header', '');
$propeller_footer = get_option('mrshort_propeller_footer', '');
$fallback_enabled = get_option('mrshort_ad_fallback_enabled', true);

// Other settings
$anti_adblock = get_option('mrshort_anti_adblock', true);
$show_blog_posts = get_option('mrshort_show_blog_posts', true);
$blog_posts_count = (int) get_option('mrshort_blog_posts_count', 6);

// Get random blog posts
$random_posts = [];
if ($show_blog_posts) {
    $random_posts = get_posts([
        'numberposts' => $blog_posts_count,
        'orderby' => 'rand',
        'post_status' => 'publish',
        'post_type' => 'post',
    ]);
}

// Theme colors
$themes = [
    'light' => [
        'bg' => '#f9fafb',
        'card' => '#ffffff',
        'card_border' => '#e5e7eb',
        'text' => '#111827',
        'text_secondary' => '#6b7280',
        'header_bg' => '#ffffff',
    ],
    'dark' => [
        'bg' => '#1a1a2e',
        'card' => '#16213e',
        'card_border' => '#2d3748',
        'text' => '#ffffff',
        'text_secondary' => '#a0aec0',
        'header_bg' => '#16213e',
    ],
];

// Auto theme - get WordPress theme colors
if ($theme === 'auto') {
    $theme_colors = $themes['light']; // fallback
} else {
    $theme_colors = $themes[$theme] ?? $themes['light'];
}

// SEO data
$link_title = isset($link_data['link']['title']) ? $link_data['link']['title'] : 'Link';
$site_name = get_bloginfo('name');
$site_description = get_bloginfo('description');
$canonical_url = home_url('/go/' . $code);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- SEO Meta Tags -->
    <title><?php echo esc_html($link_title); ?> - <?php echo esc_html($site_name); ?></title>
    <meta name="description" content="<?php echo esc_attr($site_description); ?>">
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="<?php echo esc_url($canonical_url); ?>">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo esc_attr($link_title); ?> - <?php echo esc_attr($site_name); ?>">
    <meta property="og:description" content="<?php echo esc_attr($site_description); ?>">
    <meta property="og:url" content="<?php echo esc_url($canonical_url); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo esc_attr($link_title); ?>">
    
    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "<?php echo esc_js($link_title); ?>",
        "description": "<?php echo esc_js($site_description); ?>",
        "url": "<?php echo esc_url($canonical_url); ?>",
        "isPartOf": {
            "@type": "WebSite",
            "name": "<?php echo esc_js($site_name); ?>",
            "url": "<?php echo esc_url(home_url()); ?>"
        }
    }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?php wp_head(); ?>
    
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --bg: <?php echo $theme_colors['bg']; ?>;
            --card: <?php echo $theme_colors['card']; ?>;
            --card-border: <?php echo $theme_colors['card_border']; ?>;
            --text: <?php echo $theme_colors['text']; ?>;
            --text-secondary: <?php echo $theme_colors['text_secondary']; ?>;
            --header-bg: <?php echo $theme_colors['header_bg']; ?>;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }
        
        .site-header {
            background: var(--header-bg);
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 40;
        }
        
        .header-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .site-logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }
        
        .header-timer {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--card-border);
            padding: 8px 16px;
            border-radius: 9999px;
        }
        
        .timer-circle-small {
            position: relative;
            width: 32px;
            height: 32px;
        }
        
        .timer-circle-small svg { transform: rotate(-90deg); }
        
        .timer-circle-small .timer-text {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }
        
        .progress-bar-container {
            height: 4px;
            background: var(--card-border);
        }
        
        .progress-bar {
            height: 100%;
            background: var(--primary);
            transition: width 1s linear;
        }
        
        .main-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px 16px;
        }
        
        .grid-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }
        
        @media (min-width: 1024px) {
            .grid-layout { grid-template-columns: 180px 1fr 180px; }
        }
        
        .skyscraper { display: none; }
        @media (min-width: 1024px) { .skyscraper { display: block; } }
        
        .skyscraper-inner {
            position: sticky;
            top: 80px;
            background: var(--card);
            border-radius: 8px;
            border: 1px solid var(--card-border);
            padding: 8px;
        }
        
        .ad-label {
            font-size: 11px;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 8px;
        }
        
        .ad-container {
            min-height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--card);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .ad-placeholder {
            width: 160px;
            height: 600px;
            background: var(--card-border);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            margin: 0 auto;
        }
        
        .ad-placeholder span {
            color: var(--text-secondary);
            font-size: 12px;
            text-align: center;
        }
        
        .center-content {
            background: var(--card);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid var(--card-border);
            padding: 24px;
        }
        
        @media (min-width: 768px) { .center-content { padding: 32px; } }
        
        .top-banner { margin-bottom: 24px; }
        
        .banner-728 {
            width: 100%;
            max-width: 728px;
            min-height: 90px;
            background: var(--card-border);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 auto;
            overflow: hidden;
        }
        
        .timer-section {
            text-align: center;
            margin-bottom: 24px;
        }
        
        .timer-circle {
            position: relative;
            width: 96px;
            height: 96px;
            margin: 0 auto 16px;
        }
        
        .timer-circle svg {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }
        
        .timer-circle .timer-ring-bg {
            fill: transparent;
            stroke: var(--card-border);
            stroke-width: 10;
        }
        
        .timer-circle .timer-ring {
            fill: transparent;
            stroke: var(--primary);
            stroke-width: 10;
            stroke-linecap: round;
            transition: stroke-dashoffset 1s linear;
        }
        
        .timer-circle .timer-value {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .timer-title {
            font-size: 18px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 4px;
        }
        
        .timer-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
        }
        
        .middle-banner { margin-bottom: 24px; }
        
        .banner-300 {
            width: 300px;
            min-height: 250px;
            background: var(--card-border);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 auto;
            overflow: hidden;
        }
        
        .get-link-btn {
            width: 100%;
            padding: 16px 24px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
        }
        
        .get-link-btn.disabled {
            background: var(--card-border);
            color: var(--text-secondary);
            cursor: not-allowed;
        }
        
        .get-link-btn.enabled {
            background: var(--primary);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .get-link-btn.enabled:hover { background: var(--primary-hover); }
        
        .get-link-btn.enabled::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            animation: btn-shine 3s infinite;
        }
        
        @keyframes btn-shine {
            0% { left: -50%; }
            100% { left: 150%; }
        }
        
        .spin { animation: spin 1s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        
        .bottom-banner { margin-top: 24px; }
        
        .blog-section {
            margin-top: 32px;
            padding-top: 32px;
            border-top: 1px solid var(--card-border);
        }
        
        .blog-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 16px;
        }
        
        @media (min-width: 640px) { .blog-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (min-width: 1024px) { .blog-grid { grid-template-columns: repeat(3, 1fr); } }
        
        .blog-card {
            background: var(--bg);
            border: 1px solid var(--card-border);
            border-radius: 8px;
            overflow: hidden;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }
        
        .blog-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .blog-image {
            width: 100%;
            height: 140px;
            object-fit: cover;
        }
        
        .blog-placeholder-img {
            width: 100%;
            height: 140px;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
        }
        
        .blog-content { padding: 12px; }
        
        .blog-card-title {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 6px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: var(--text);
        }
        
        .blog-excerpt {
            font-size: 12px;
            color: var(--text-secondary);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .blog-meta {
            font-size: 11px;
            color: var(--text-secondary);
            margin-top: 8px;
        }
        
        .site-footer {
            background: var(--card);
            border-top: 1px solid var(--card-border);
            margin-top: 32px;
        }
        
        .footer-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        
        @media (min-width: 768px) {
            .footer-content { flex-direction: row; justify-content: space-between; }
        }
        
        .footer-text { font-size: 14px; color: var(--text-secondary); }
        .footer-links { display: flex; gap: 24px; }
        .footer-links a { font-size: 14px; color: var(--text-secondary); text-decoration: none; }
        .footer-links a:hover { color: var(--primary); }
        
        .adblock-modal {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
        }
        
        .adblock-modal.active { display: flex; }
        
        .adblock-content {
            background: var(--card);
            border-radius: 12px;
            padding: 32px;
            max-width: 400px;
            margin: 16px;
            text-align: center;
        }
        
        .adblock-icon {
            width: 64px;
            height: 64px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }
        
        .adblock-icon .material-icons { color: #ef4444; font-size: 32px; }
        .adblock-title { font-size: 20px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
        .adblock-text { color: var(--text-secondary); margin-bottom: 24px; }
        
        .adblock-btn {
            background: var(--primary);
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
        }
        
        .adblock-btn:hover { background: var(--primary-hover); }
        
        /* Hidden ad slots for fallback */
        .ad-slot { display: none; }
        .ad-slot.active { display: block; }
    </style>
</head>
<body>

<?php if ($anti_adblock): ?>
<div class="adblock-modal" id="adblock-modal">
    <div class="adblock-content">
        <div class="adblock-icon">
            <span class="material-icons">block</span>
        </div>
        <h2 class="adblock-title">AdBlock Detected!</h2>
        <p class="adblock-text">Please disable your ad blocker to continue.</p>
        <button onclick="location.reload()" class="adblock-btn">I've Disabled It – Reload</button>
    </div>
</div>
<?php endif; ?>

<header class="site-header">
    <div class="header-content">
        <a href="<?php echo home_url(); ?>" class="site-logo"><?php bloginfo('name'); ?></a>
        
        <div class="header-timer">
            <div class="timer-circle-small">
                <svg viewBox="0 0 36 36">
                    <circle cx="18" cy="18" r="15" fill="transparent" stroke="<?php echo $theme_colors['card_border']; ?>" stroke-width="3"></circle>
                    <circle id="timer-progress-small" cx="18" cy="18" r="15" fill="transparent" stroke="#4F46E5" stroke-width="3" stroke-linecap="round" stroke-dasharray="94.2" stroke-dashoffset="0"></circle>
                </svg>
                <span class="timer-text" id="timer-small"><?php echo esc_html($actual_wait_time); ?></span>
            </div>
            <span style="font-size: 14px; color: var(--text-secondary);">seconds</span>
        </div>
    </div>
    
    <div class="progress-bar-container">
        <div class="progress-bar" id="progress-bar" style="width: 100%"></div>
    </div>
</header>

<main class="main-container">
    <div class="grid-layout">
        
        <!-- Left Skyscraper -->
        <div class="skyscraper">
            <div class="skyscraper-inner">
                <p class="ad-label">Advertisement</p>
                <div class="ad-placeholder">
                    <?php if ($adsense_sidebar): ?>
                        <?php echo $adsense_sidebar; ?>
                    <?php else: ?>
                        <span>160x600<br>Skyscraper</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Center Content -->
        <div class="center-content">
            
            <!-- Top Banner with Fallback -->
            <div class="top-banner">
                <p class="ad-label">Advertisement</p>
                <div class="banner-728" id="header-ad-container">
                    <div class="ad-slot active" id="adsense-header"><?php echo $adsense_header; ?></div>
                    <?php if ($fallback_enabled): ?>
                    <div class="ad-slot" id="medianet-header"><?php echo $medianet_header; ?></div>
                    <div class="ad-slot" id="propeller-header"><?php echo $propeller_header; ?></div>
                    <?php endif; ?>
                    <?php if (empty($adsense_header) && empty($medianet_header) && empty($propeller_header)): ?>
                        <span style="color: var(--text-secondary);">728x90 Leaderboard</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Timer Section -->
            <div class="timer-section">
                <div class="timer-circle">
                    <svg viewBox="0 0 120 120">
                        <circle class="timer-ring-bg" cx="60" cy="60" r="54"></circle>
                        <circle class="timer-ring" id="timer-progress" cx="60" cy="60" r="54" stroke-dasharray="339.292" stroke-dashoffset="0"></circle>
                    </svg>
                    <div class="timer-value" id="timer-countdown"><?php echo esc_html($actual_wait_time); ?></div>
                </div>
                <p class="timer-title">Your link is almost ready!</p>
                <p class="timer-subtitle">Please wait while we prepare your destination...</p>
            </div>
            
            <!-- Middle Banner with Fallback -->
            <div class="middle-banner">
                <div class="banner-300" id="footer-ad-container">
                    <div class="ad-slot active" id="adsense-footer"><?php echo $adsense_footer; ?></div>
                    <?php if ($fallback_enabled): ?>
                    <div class="ad-slot" id="medianet-footer"><?php echo $medianet_footer; ?></div>
                    <div class="ad-slot" id="propeller-footer"><?php echo $propeller_footer; ?></div>
                    <?php endif; ?>
                    <?php if (empty($adsense_footer) && empty($medianet_footer) && empty($propeller_footer)): ?>
                        <span style="color: var(--text-secondary);">300x250 Banner</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Get Link Button -->
            <button id="get-link-btn" class="get-link-btn disabled" disabled data-ab-variant="<?php echo esc_attr($ab_enabled ? 'wait_' . $actual_wait_time : ''); ?>">
                <span class="material-icons spin">hourglass_empty</span>
                <span>Please wait...</span>
            </button>
            
            <!-- Bottom Banner -->
            <div class="bottom-banner">
                <div class="banner-300">
                    <span style="color: var(--text-secondary);">300x250 Banner</span>
                </div>
            </div>
            
            <?php if (!empty($random_posts)): ?>
            <section class="blog-section">
                <h2 class="blog-title">📚 Latest Articles</h2>
                <div class="blog-grid">
                    <?php foreach ($random_posts as $post): ?>
                        <?php
                        $thumbnail = get_the_post_thumbnail_url($post->ID, 'medium');
                        $excerpt = wp_trim_words(get_the_excerpt($post), 15, '...');
                        if (empty($excerpt)) {
                            $excerpt = wp_trim_words($post->post_content, 15, '...');
                        }
                        ?>
                        <a href="<?php echo get_permalink($post->ID); ?>" class="blog-card" target="_blank">
                            <?php if ($thumbnail): ?>
                                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($post->post_title); ?>" class="blog-image">
                            <?php else: ?>
                                <div class="blog-placeholder-img">📄</div>
                            <?php endif; ?>
                            <div class="blog-content">
                                <h3 class="blog-card-title"><?php echo esc_html($post->post_title); ?></h3>
                                <p class="blog-excerpt"><?php echo esc_html($excerpt); ?></p>
                                <p class="blog-meta"><?php echo get_the_date('M j, Y', $post->ID); ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>
        
        <!-- Right Skyscraper -->
        <div class="skyscraper">
            <div class="skyscraper-inner">
                <p class="ad-label">Advertisement</p>
                <div class="ad-placeholder">
                    <?php if ($adsense_sidebar): ?>
                        <?php echo $adsense_sidebar; ?>
                    <?php else: ?>
                        <span>160x600<br>Skyscraper</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="site-footer">
    <div class="footer-content">
        <p class="footer-text">© <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Use</a>
        </div>
    </div>
</footer>

<script>
(function() {
    const totalTime = <?php echo (int) $actual_wait_time; ?>;
    let timeLeft = totalTime;
    let timerPaused = false;
    const abEnabled = <?php echo $ab_enabled ? 'true' : 'false'; ?>;
    const fallbackEnabled = <?php echo $fallback_enabled ? 'true' : 'false'; ?>;
    
    const timerCountdown = document.getElementById('timer-countdown');
    const timerSmall = document.getElementById('timer-small');
    const timerProgress = document.getElementById('timer-progress');
    const timerProgressSmall = document.getElementById('timer-progress-small');
    const progressBar = document.getElementById('progress-bar');
    const btn = document.getElementById('get-link-btn');
    
    const radius = 54, circumference = 2 * Math.PI * radius;
    const radiusSmall = 15, circumferenceSmall = 2 * Math.PI * radiusSmall;
    
    timerProgress.style.strokeDasharray = `${circumference} ${circumference}`;
    timerProgressSmall.style.strokeDasharray = `${circumferenceSmall} ${circumferenceSmall}`;
    
    function setProgress(percent) {
        timerProgress.style.strokeDashoffset = circumference - (percent / 100) * circumference;
        timerProgressSmall.style.strokeDashoffset = circumferenceSmall - (percent / 100) * circumferenceSmall;
        progressBar.style.width = `${percent}%`;
    }
    
    setProgress(100);
    
    const interval = setInterval(function() {
        if (timerPaused) return;
        timeLeft--;
        timerCountdown.textContent = timeLeft;
        timerSmall.textContent = timeLeft;
        setProgress(100 * (timeLeft / totalTime));
        
        if (timeLeft <= 0) {
            clearInterval(interval);
            timerCountdown.innerHTML = '<span class="material-icons" style="font-size:30px;">check</span>';
            timerSmall.innerHTML = '<span class="material-icons" style="font-size:14px;">check</span>';
            
            btn.disabled = false;
            btn.classList.remove('disabled');
            btn.classList.add('enabled');
            btn.innerHTML = '<span>Get Your Link</span><span class="material-icons">arrow_forward</span>';
            btn.onclick = function() {
                // Track A/B click if enabled
                if (abEnabled && btn.dataset.abVariant) {
                    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'action=mrshort_ab_click&variant=' + btn.dataset.abVariant
                    });
                }
                window.location.href = '<?php echo esc_url($next_url); ?>';
            };
        }
    }, 1000);
    
    <?php if ($fallback_enabled): ?>
    // Ad Fallback System
    setTimeout(function() {
        checkAdLoaded('header-ad-container', ['adsense-header', 'medianet-header', 'propeller-header']);
        checkAdLoaded('footer-ad-container', ['adsense-footer', 'medianet-footer', 'propeller-footer']);
    }, 2000);
    
    function checkAdLoaded(containerId, slotIds) {
        var container = document.getElementById(containerId);
        if (!container) return;
        
        for (var i = 0; i < slotIds.length; i++) {
            var slot = document.getElementById(slotIds[i]);
            if (slot && slot.innerHTML.trim() !== '' && hasVisibleAd(slot)) {
                // This ad loaded, show it and hide others
                slotIds.forEach(function(id) {
                    var el = document.getElementById(id);
                    if (el) el.classList.remove('active');
                });
                slot.classList.add('active');
                return;
            }
        }
    }
    
    function hasVisibleAd(element) {
        return element.offsetHeight > 0 && element.innerHTML.trim().length > 50;
    }
    <?php endif; ?>
    
    <?php if ($anti_adblock): ?>
    // Anti-adblock detection
    setTimeout(function() {
        var testAd = document.createElement('div');
        testAd.innerHTML = '&nbsp;';
        testAd.className = 'adsbox ad-placement advertisement';
        testAd.style.cssText = 'position:absolute;left:-9999px;width:1px;height:1px;';
        document.body.appendChild(testAd);
        
        setTimeout(function() {
            if (testAd.offsetHeight === 0 || testAd.clientHeight === 0) {
                timerPaused = true;
                document.getElementById('adblock-modal').classList.add('active');
            }
            testAd.remove();
        }, 100);
    }, 500);
    <?php endif; ?>
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
