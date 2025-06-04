<?php
/**
 * REST API Class
 * 
 * Handles the REST API endpoints for the plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IGPR_REST_API {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    
    /**
     * Register REST API routes
     */
    public function register_routes() {
        register_rest_route('igpr/v1', '/settings', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'get_settings'),
                'permission_callback' => array($this, 'permissions_check'),
            ),
            array(
                'methods'             => WP_REST_Server::CREATABLE,
                'callback'            => array($this, 'update_settings'),
                'permission_callback' => array($this, 'permissions_check'),
            ),
        ));
    }
    
    /**
     * Check if user has permission to access the API
     */
    public function permissions_check() {
        return current_user_can('manage_options');
    }
    
    /**
     * Get plugin settings
     */
    public function get_settings() {
        $settings = get_option('igpr_settings', array());
        
        return rest_ensure_response($settings);
    }
    
    /**
     * Update plugin settings
     */
    public function update_settings($request) {
        $settings = $request->get_params();
        
        // Sanitize settings
        $sanitized = array();
        
        // Sanitize default category
        $sanitized['default_category'] = isset($settings['default_category']) ? sanitize_text_field($settings['default_category']) : get_option('default_category');
        
        // Sanitize moderation
        $sanitized['moderation'] = isset($settings['moderation']) ? sanitize_text_field($settings['moderation']) : 'on';
        
        // Sanitize submission limit
        $sanitized['submission_limit'] = isset($settings['submission_limit']) ? sanitize_text_field($settings['submission_limit']) : '3';
        
        // Sanitize auto-reply
        $sanitized['auto_reply'] = isset($settings['auto_reply']) ? sanitize_text_field($settings['auto_reply']) : 'off';
        
        // Sanitize default theme
        $sanitized['default_theme'] = isset($settings['default_theme']) && in_array($settings['default_theme'], array('light', 'dark')) ? $settings['default_theme'] : 'light';
        
        // Sanitize email subjects
        $sanitized['admin_email_subject'] = isset($settings['admin_email_subject']) ? sanitize_text_field($settings['admin_email_subject']) : '';
        $sanitized['auto_reply_subject'] = isset($settings['auto_reply_subject']) ? sanitize_text_field($settings['auto_reply_subject']) : '';
        $sanitized['approve_email_subject'] = isset($settings['approve_email_subject']) ? sanitize_text_field($settings['approve_email_subject']) : '';
        $sanitized['reject_email_subject'] = isset($settings['reject_email_subject']) ? sanitize_text_field($settings['reject_email_subject']) : '';
        
        // Sanitize email templates
        $sanitized['admin_email_template'] = isset($settings['admin_email_template']) ? wp_kses_post($settings['admin_email_template']) : '';
        $sanitized['auto_reply_template'] = isset($settings['auto_reply_template']) ? wp_kses_post($settings['auto_reply_template']) : '';
        $sanitized['approve_email_template'] = isset($settings['approve_email_template']) ? wp_kses_post($settings['approve_email_template']) : '';
        $sanitized['reject_email_template'] = isset($settings['reject_email_template']) ? wp_kses_post($settings['reject_email_template']) : '';
        
        // Update settings
        update_option('igpr_settings', $sanitized);
        
        return rest_ensure_response(array(
            'success' => true,
            'message' => __('Settings saved successfully.', 'instant-guest-post-request'),
            'settings' => $sanitized
        ));
    }
}
