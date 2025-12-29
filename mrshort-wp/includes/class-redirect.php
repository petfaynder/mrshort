<?php
/**
 * MrShort Redirect Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class MrShort_Redirect {
    
    private $api;
    
    public function __construct($api) {
        $this->api = $api;
    }
    
    /**
     * Handle redirect request
     */
    public function handle($code) {
        $page = max(1, (int) get_query_var('mrshort_page', 1));
        $pages_count = (int) get_option('mrshort_pages_count', 2);
        $wait_time = (int) get_option('mrshort_wait_time', 5);
        
        // Get link data from API
        $link_data = $this->api->get_link($code);
        
        if (is_wp_error($link_data)) {
            // Link not found or API error - redirect to homepage
            wp_redirect(home_url());
            exit;
        }
        
        // Check if this is the last page
        $is_last_page = $page >= $pages_count;
        
        if ($is_last_page) {
            // Notify MrShort about the click
            $this->api->record_click($code, $pages_count);
            
            // Redirect to MrShort
            $redirect_to = isset($link_data['redirect_to']) ? $link_data['redirect_to'] : '';
            if (!empty($redirect_to)) {
                wp_redirect($redirect_to);
                exit;
            }
        }
        
        // Calculate next page URL
        $next_url = home_url('/go/' . $code . '/page/' . ($page + 1));
        if ($is_last_page && isset($link_data['redirect_to'])) {
            $next_url = $link_data['redirect_to'];
        }
        
        // Render the redirect page
        $this->render_page([
            'code' => $code,
            'page' => $page,
            'pages_count' => $pages_count,
            'wait_time' => $wait_time,
            'next_url' => $next_url,
            'is_last_page' => $is_last_page,
            'link_data' => $link_data,
        ]);
        
        exit;
    }
    
    /**
     * Render the redirect page
     */
    private function render_page($data) {
        $template = MRSHORT_PLUGIN_DIR . 'templates/redirect-page.php';
        
        if (file_exists($template)) {
            extract($data);
            include $template;
        } else {
            // Fallback inline template
            $this->render_inline_template($data);
        }
    }
    
    /**
     * Inline fallback template
     */
    private function render_inline_template($data) {
        extract($data);
        
        $adsense_header = get_option('mrshort_adsense_header', '');
        $adsense_footer = get_option('mrshort_adsense_footer', '');
        $anti_adblock = get_option('mrshort_anti_adblock', true);
        
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Redirecting... - <?php bloginfo('name'); ?></title>
            <?php wp_head(); ?>
        </head>
        <body class="mrshort-redirect-page">
            
            <?php if ($anti_adblock): ?>
            <div id="mrshort-adblock-warning" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.9); z-index:9999; padding:50px; text-align:center; color:white;">
                <h1>Ad Blocker Detected</h1>
                <p>Please disable your ad blocker to continue.</p>
                <button onclick="location.reload()">I've Disabled It - Reload</button>
            </div>
            <?php endif; ?>
            
            <div class="mrshort-container">
                <header class="mrshort-header">
                    <h1><?php bloginfo('name'); ?></h1>
                    <div class="mrshort-progress">
                        Step <?php echo esc_html($page); ?> of <?php echo esc_html($pages_count); ?>
                    </div>
                </header>
                
                <div class="mrshort-ads-top">
                    <?php echo $adsense_header; ?>
                </div>
                
                <div class="mrshort-timer-section">
                    <div class="mrshort-timer" id="timer"><?php echo esc_html($wait_time); ?></div>
                    <p>Please wait...</p>
                    <div class="mrshort-progress-bar">
                        <div class="mrshort-progress-fill" id="progress-fill"></div>
                    </div>
                </div>
                
                <div class="mrshort-button-section">
                    <a href="<?php echo esc_url($next_url); ?>" id="continue-btn" class="mrshort-btn" style="display:none;">
                        <?php echo $is_last_page ? 'Get Link' : 'Continue'; ?>
                    </a>
                </div>
                
                <div class="mrshort-ads-bottom">
                    <?php echo $adsense_footer; ?>
                </div>
            </div>
            
            <script>
            (function() {
                var waitTime = <?php echo (int) $wait_time; ?>;
                var timer = document.getElementById('timer');
                var btn = document.getElementById('continue-btn');
                var fill = document.getElementById('progress-fill');
                var totalTime = waitTime;
                
                var interval = setInterval(function() {
                    waitTime--;
                    timer.textContent = waitTime;
                    fill.style.width = ((totalTime - waitTime) / totalTime * 100) + '%';
                    
                    if (waitTime <= 0) {
                        clearInterval(interval);
                        btn.style.display = 'inline-block';
                        timer.textContent = '✓';
                    }
                }, 1000);
                
                <?php if ($anti_adblock): ?>
                // Simple anti-adblock check
                setTimeout(function() {
                    var testAd = document.createElement('div');
                    testAd.innerHTML = '&nbsp;';
                    testAd.className = 'adsbox ad-placement advertisement';
                    testAd.style.position = 'absolute';
                    testAd.style.left = '-9999px';
                    document.body.appendChild(testAd);
                    
                    setTimeout(function() {
                        if (testAd.offsetHeight === 0) {
                            document.getElementById('mrshort-adblock-warning').style.display = 'flex';
                            document.getElementById('mrshort-adblock-warning').style.alignItems = 'center';
                            document.getElementById('mrshort-adblock-warning').style.justifyContent = 'center';
                            document.getElementById('mrshort-adblock-warning').style.flexDirection = 'column';
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
        <?php
    }
}
