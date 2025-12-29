<?php
/**
 * MrShort API Communication Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class MrShort_API {
    
    private $api_url;
    private $api_token;
    
    public function __construct() {
        $this->api_url = rtrim(get_option('mrshort_api_url', ''), '/');
        $this->api_token = get_option('mrshort_api_token', '');
    }
    
    /**
     * Get link data from MrShort API
     */
    public function get_link($code) {
        if (empty($this->api_url) || empty($this->api_token)) {
            return new WP_Error('not_configured', 'MrShort API not configured');
        }
        
        $response = wp_remote_get($this->api_url . '/api/wp/link/' . $code, [
            'headers' => [
                'X-WP-Token' => $this->api_token,
                'Accept' => 'application/json',
            ],
            'timeout' => 15,
        ]);
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            return new WP_Error(
                'api_error',
                isset($data['message']) ? $data['message'] : 'Unknown error'
            );
        }
        
        return $data;
    }
    
    /**
     * Notify MrShort about a click
     */
    public function record_click($code, $pages_viewed = 0) {
        if (empty($this->api_url) || empty($this->api_token)) {
            return false;
        }
        
        $response = wp_remote_post($this->api_url . '/api/wp/click', [
            'headers' => [
                'X-WP-Token' => $this->api_token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'code' => $code,
                'ip' => $this->get_client_ip(),
                'country' => '',
                'pages_viewed' => $pages_viewed,
            ]),
            'timeout' => 15,
        ]);
        
        return !is_wp_error($response);
    }
    
    /**
     * Test API connection
     */
    public function test_connection() {
        if (empty($this->api_url) || empty($this->api_token)) {
            return new WP_Error('not_configured', 'API URL and Token are required');
        }
        
        $response = wp_remote_get($this->api_url . '/api/wp/test', [
            'headers' => [
                'X-WP-Token' => $this->api_token,
                'Accept' => 'application/json',
            ],
            'timeout' => 15,
        ]);
        
        if (is_wp_error($response)) {
            return $response;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (wp_remote_retrieve_response_code($response) !== 200) {
            return new WP_Error(
                'connection_failed',
                isset($data['message']) ? $data['message'] : 'Connection failed'
            );
        }
        
        return $data;
    }
    
    /**
     * Get client IP address
     */
    private function get_client_ip() {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR',
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        
        return '0.0.0.0';
    }
}
