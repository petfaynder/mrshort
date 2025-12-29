<?php
/**
 * Plugin Name: MrShort WordPress Integration
 * Plugin URI: https://mrshort.com
 * Description: Connect your WordPress blog with MrShort for AdSense monetization on redirect pages.
 * Version: 1.0.0
 * Author: MrShort
 * Author URI: https://mrshort.com
 * License: GPL v2 or later
 * Text Domain: mrshort-wp
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MRSHORT_VERSION', '1.0.0');
define('MRSHORT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MRSHORT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoload classes
require_once MRSHORT_PLUGIN_DIR . 'includes/class-api.php';
require_once MRSHORT_PLUGIN_DIR . 'includes/class-admin.php';
require_once MRSHORT_PLUGIN_DIR . 'includes/class-redirect.php';

/**
 * Main plugin class
 */
class MrShort_WordPress {
    
    private static $instance = null;
    private $api;
    private $admin;
    private $redirect;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->api = new MrShort_API();
        $this->redirect = new MrShort_Redirect($this->api);
        
        if (is_admin()) {
            $this->admin = new MrShort_Admin($this->api);
        }
        
        // Register activation/deactivation hooks
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        
        // Add rewrite rules
        add_action('init', [$this, 'add_rewrite_rules']);
        add_filter('query_vars', [$this, 'add_query_vars']);
        add_action('template_redirect', [$this, 'handle_redirect']);
        
        // Enqueue assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }
    
    public function activate() {
        $this->add_rewrite_rules();
        flush_rewrite_rules();
        
        // Set default options
        $defaults = [
            'mrshort_api_url' => '',
            'mrshort_api_token' => '',
            'mrshort_pages_count' => 2,
            'mrshort_wait_time' => 5,
            'mrshort_adsense_header' => '',
            'mrshort_adsense_footer' => '',
            'mrshort_anti_adblock' => true,
        ];
        
        foreach ($defaults as $key => $value) {
            if (get_option($key) === false) {
                add_option($key, $value);
            }
        }
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    public function add_rewrite_rules() {
        add_rewrite_rule(
            '^go/([a-zA-Z0-9]+)/?$',
            'index.php?mrshort_code=$matches[1]',
            'top'
        );
        add_rewrite_rule(
            '^go/([a-zA-Z0-9]+)/page/([0-9]+)/?$',
            'index.php?mrshort_code=$matches[1]&mrshort_page=$matches[2]',
            'top'
        );
    }
    
    public function add_query_vars($vars) {
        $vars[] = 'mrshort_code';
        $vars[] = 'mrshort_page';
        return $vars;
    }
    
    public function handle_redirect() {
        $code = get_query_var('mrshort_code');
        if (!empty($code)) {
            $this->redirect->handle($code);
        }
    }
    
    public function enqueue_scripts() {
        if (get_query_var('mrshort_code')) {
            wp_enqueue_style(
                'mrshort-redirect',
                MRSHORT_PLUGIN_URL . 'assets/style.css',
                [],
                MRSHORT_VERSION
            );
            wp_enqueue_script(
                'mrshort-redirect',
                MRSHORT_PLUGIN_URL . 'assets/script.js',
                [],
                MRSHORT_VERSION,
                true
            );
        }
    }
}

// Initialize plugin
function mrshort_init() {
    return MrShort_WordPress::get_instance();
}
add_action('plugins_loaded', 'mrshort_init');
