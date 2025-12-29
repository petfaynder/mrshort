<?php
/**
 * MrShort Admin Settings Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class MrShort_Admin {
    
    private $api;
    
    public function __construct($api) {
        $this->api = $api;
        
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        
        // AJAX handlers
        add_action('wp_ajax_mrshort_test_connection', [$this, 'ajax_test_connection']);
        add_action('wp_ajax_mrshort_ab_click', [$this, 'ajax_ab_click']);
        add_action('wp_ajax_nopriv_mrshort_ab_click', [$this, 'ajax_ab_click']);
    }
    
    public function ajax_ab_click() {
        $variant = sanitize_text_field($_POST['variant'] ?? '');
        if (empty($variant)) {
            wp_send_json_error('No variant');
        }
        
        $ab_stats = get_option('mrshort_ab_stats', []);
        if (!isset($ab_stats[$variant])) {
            $ab_stats[$variant] = ['impressions' => 0, 'clicks' => 0];
        }
        $ab_stats[$variant]['clicks']++;
        update_option('mrshort_ab_stats', $ab_stats);
        
        wp_send_json_success(['variant' => $variant, 'clicks' => $ab_stats[$variant]['clicks']]);
    }
    
    public function add_menu() {
        add_options_page(
            'MrShort Settings',
            'MrShort',
            'manage_options',
            'mrshort-settings',
            [$this, 'render_settings_page']
        );
    }
    
    public function register_settings() {
        // API Settings
        register_setting('mrshort_settings', 'mrshort_api_url');
        register_setting('mrshort_settings', 'mrshort_api_token');
        register_setting('mrshort_settings', 'mrshort_pages_count');
        register_setting('mrshort_settings', 'mrshort_wait_time');
        
        // Theme Settings
        register_setting('mrshort_settings', 'mrshort_theme');
        
        // Ad Settings
        register_setting('mrshort_settings', 'mrshort_adsense_header');
        register_setting('mrshort_settings', 'mrshort_adsense_footer');
        register_setting('mrshort_settings', 'mrshort_adsense_sidebar');
        register_setting('mrshort_settings', 'mrshort_medianet_header');
        register_setting('mrshort_settings', 'mrshort_medianet_footer');
        register_setting('mrshort_settings', 'mrshort_propeller_header');
        register_setting('mrshort_settings', 'mrshort_propeller_footer');
        register_setting('mrshort_settings', 'mrshort_ad_fallback_enabled');
        register_setting('mrshort_settings', 'mrshort_ad_priority');
        
        // Other Settings
        register_setting('mrshort_settings', 'mrshort_anti_adblock');
        register_setting('mrshort_settings', 'mrshort_show_blog_posts');
        register_setting('mrshort_settings', 'mrshort_blog_posts_count');
        
        // A/B Test Settings
        register_setting('mrshort_settings', 'mrshort_ab_enabled');
        register_setting('mrshort_settings', 'mrshort_ab_variants');
    }
    
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'settings_page_mrshort-settings') {
            return;
        }
        
        wp_enqueue_script(
            'mrshort-admin',
            MRSHORT_PLUGIN_URL . 'assets/admin.js',
            ['jquery'],
            MRSHORT_VERSION,
            true
        );
        
        wp_localize_script('mrshort-admin', 'mrshort_admin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mrshort_admin'),
        ]);
    }
    
    public function ajax_test_connection() {
        check_ajax_referer('mrshort_admin', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $result = $this->api->test_connection();
        
        if (is_wp_error($result)) {
            wp_send_json_error($result->get_error_message());
        }
        
        wp_send_json_success($result);
    }
    
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <form action="options.php" method="post">
                <?php settings_fields('mrshort_settings'); ?>
                
                <h2 class="title">API Connection</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="mrshort_api_url">MrShort Site URL</label></th>
                        <td>
                            <input type="url" name="mrshort_api_url" id="mrshort_api_url" 
                                   value="<?php echo esc_attr(get_option('mrshort_api_url')); ?>" 
                                   class="regular-text" placeholder="https://mrshort.com">
                            <p class="description">Your MrShort installation URL (without trailing slash)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mrshort_api_token">API Token</label></th>
                        <td>
                            <input type="password" name="mrshort_api_token" id="mrshort_api_token" 
                                   value="<?php echo esc_attr(get_option('mrshort_api_token')); ?>" 
                                   class="regular-text">
                            <button type="button" id="mrshort_test_connection" class="button">Test Connection</button>
                            <span id="mrshort_connection_status"></span>
                            <p class="description">Copy from MrShort Admin Panel → Settings → WordPress</p>
                        </td>
                    </tr>
                </table>
                
                <h2 class="title">Theme Settings</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="mrshort_theme">Page Theme</label></th>
                        <td>
                            <select name="mrshort_theme" id="mrshort_theme">
                                <option value="light" <?php selected(get_option('mrshort_theme', 'light'), 'light'); ?>>Light Theme</option>
                                <option value="dark" <?php selected(get_option('mrshort_theme', 'light'), 'dark'); ?>>Dark Theme</option>
                                <option value="auto" <?php selected(get_option('mrshort_theme', 'light'), 'auto'); ?>>WordPress Theme (Auto)</option>
                            </select>
                            <p class="description">Choose the appearance of redirect pages</p>
                        </td>
                    </tr>
                </table>
                
                <h2 class="title">Redirect Page Settings</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="mrshort_pages_count">Number of Pages</label></th>
                        <td>
                            <input type="number" name="mrshort_pages_count" id="mrshort_pages_count" 
                                   value="<?php echo esc_attr(get_option('mrshort_pages_count', 2)); ?>" 
                                   min="1" max="5" class="small-text">
                            <p class="description">How many ad pages to show (1-5)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mrshort_wait_time">Wait Time (seconds)</label></th>
                        <td>
                            <input type="number" name="mrshort_wait_time" id="mrshort_wait_time" 
                                   value="<?php echo esc_attr(get_option('mrshort_wait_time', 5)); ?>" 
                                   min="3" max="30" class="small-text">
                            <p class="description">How long visitors wait on each page (3-30)</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mrshort_anti_adblock">Anti-AdBlock</label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="mrshort_anti_adblock" id="mrshort_anti_adblock" 
                                       value="1" <?php checked(get_option('mrshort_anti_adblock', true)); ?>>
                                Enable Anti-AdBlock detection
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mrshort_show_blog_posts">Show Blog Posts</label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="mrshort_show_blog_posts" id="mrshort_show_blog_posts" 
                                       value="1" <?php checked(get_option('mrshort_show_blog_posts', true)); ?>>
                                Display random blog posts on redirect pages
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mrshort_blog_posts_count">Blog Posts Count</label></th>
                        <td>
                            <input type="number" name="mrshort_blog_posts_count" id="mrshort_blog_posts_count" 
                                   value="<?php echo esc_attr(get_option('mrshort_blog_posts_count', 6)); ?>" 
                                   min="3" max="12" class="small-text">
                        </td>
                    </tr>
                </table>
                
                <h2 class="title">A/B Testing</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="mrshort_ab_enabled">Enable A/B Testing</label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="mrshort_ab_enabled" id="mrshort_ab_enabled" 
                                       value="1" <?php checked(get_option('mrshort_ab_enabled', false)); ?>>
                                Automatically test different configurations
                            </label>
                            <p class="description">System will test wait times (5s, 7s, 10s) and optimize for best CTR</p>
                        </td>
                    </tr>
                </table>
                
                <h2 class="title">Ad Networks - Google AdSense</h2>
                <p class="description">Primary ad network. These ads will be shown first.</p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="mrshort_adsense_header">Header (728x90)</label></th>
                        <td>
                            <textarea name="mrshort_adsense_header" id="mrshort_adsense_header" 
                                      rows="4" class="large-text code"><?php echo esc_textarea(get_option('mrshort_adsense_header')); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mrshort_adsense_footer">Middle (300x250)</label></th>
                        <td>
                            <textarea name="mrshort_adsense_footer" id="mrshort_adsense_footer" 
                                      rows="4" class="large-text code"><?php echo esc_textarea(get_option('mrshort_adsense_footer')); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mrshort_adsense_sidebar">Sidebar (160x600)</label></th>
                        <td>
                            <textarea name="mrshort_adsense_sidebar" id="mrshort_adsense_sidebar" 
                                      rows="4" class="large-text code"><?php echo esc_textarea(get_option('mrshort_adsense_sidebar')); ?></textarea>
                        </td>
                    </tr>
                </table>
                
                <h2 class="title">Ad Networks - Media.net (Fallback #1)</h2>
                <p class="description">Shown if AdSense doesn't load within 2 seconds.</p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="mrshort_medianet_header">Header</label></th>
                        <td>
                            <textarea name="mrshort_medianet_header" id="mrshort_medianet_header" 
                                      rows="4" class="large-text code"><?php echo esc_textarea(get_option('mrshort_medianet_header')); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mrshort_medianet_footer">Middle</label></th>
                        <td>
                            <textarea name="mrshort_medianet_footer" id="mrshort_medianet_footer" 
                                      rows="4" class="large-text code"><?php echo esc_textarea(get_option('mrshort_medianet_footer')); ?></textarea>
                        </td>
                    </tr>
                </table>
                
                <h2 class="title">Ad Networks - PropellerAds (Fallback #2)</h2>
                <p class="description">Shown if both AdSense and Media.net fail.</p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="mrshort_propeller_header">Header</label></th>
                        <td>
                            <textarea name="mrshort_propeller_header" id="mrshort_propeller_header" 
                                      rows="4" class="large-text code"><?php echo esc_textarea(get_option('mrshort_propeller_header')); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="mrshort_propeller_footer">Middle</label></th>
                        <td>
                            <textarea name="mrshort_propeller_footer" id="mrshort_propeller_footer" 
                                      rows="4" class="large-text code"><?php echo esc_textarea(get_option('mrshort_propeller_footer')); ?></textarea>
                        </td>
                    </tr>
                </table>
                
                <h2 class="title">Ad Fallback Settings</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="mrshort_ad_fallback_enabled">Enable Fallback</label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="mrshort_ad_fallback_enabled" id="mrshort_ad_fallback_enabled" 
                                       value="1" <?php checked(get_option('mrshort_ad_fallback_enabled', true)); ?>>
                                Automatically switch to backup ad network if primary fails
                            </label>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <hr>
            <h2>Usage</h2>
            <p>Once configured, links will be accessible at: <code><?php echo esc_html(home_url('/go/{code}')); ?></code></p>
            <p>After the AdSense pages, visitors will be redirected to MrShort for your site's ads, then to the final destination.</p>
        </div>
        <?php
    }
}
