<?php
/**
 * Plugin Name: Instant Guest Post Request
 * Plugin URI: https://example.com/instant-guest-post-request
 * Description: Allow visitors to submit guest posts from the frontend without requiring login.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: instant-guest-post-request
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('IGPR_VERSION', '1.0.0');
define('IGPR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IGPR_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once IGPR_PLUGIN_DIR . 'includes/class-igpr-form-handler.php';
require_once IGPR_PLUGIN_DIR . 'includes/class-igpr-post-handler.php';
require_once IGPR_PLUGIN_DIR . 'includes/class-igpr-notification.php';
require_once IGPR_PLUGIN_DIR . 'includes/class-igpr-settings.php';

/**
 * Main plugin class
 */
class Instant_Guest_Post_Request {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize plugin components
        add_action('plugins_loaded', array($this, 'init_plugin'));
        
        // Register activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin components
     */
    public function init_plugin() {
        // Load text domain for translations
        load_plugin_textdomain('instant-guest-post-request', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Initialize form handler
        new IGPR_Form_Handler();
        
        // Initialize post handler
        new IGPR_Post_Handler();
        
        // Initialize notification system
        new IGPR_Notification();
        
        // Initialize settings page
        new IGPR_Settings();
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Set default settings
        $default_settings = array(
            'default_category' => get_option('default_category'),
            'moderation' => 'on',
            'submission_limit' => 3,
            'auto_reply' => 'off',
            'default_theme' => 'light',
            'admin_email_subject' => 'New Guest Post Submission: "{post_title}"',
            'admin_email_template' => "A new guest post was submitted:\n\nTitle: {post_title}\nSubmitted by: {author_name} ({author_email})\n\nPreview: {preview_url}\nApprove: {approve_url}\nReject: {reject_url}\n\nLogin to review: {admin_url}",
            'auto_reply_subject' => 'Thank you for your submission',
            'auto_reply_template' => "Hello {author_name},\n\nThank you for submitting your guest post \"{post_title}\" to our website.\n\nWe have received your submission and will review it shortly. We'll notify you once we've made a decision.\n\nRegards,\n{site_name}",
            'approve_email_subject' => 'Your guest post has been approved',
            'approve_email_template' => "Hello {author_name},\n\nYour guest post \"{post_title}\" has been approved and published on our website.\n\nYou can view it here: {post_url}\n\nThank you for your contribution!\n\nRegards,\n{site_name}",
            'reject_email_subject' => 'Your guest post submission',
            'reject_email_template' => "Hello {author_name},\n\nThank you for submitting your guest post \"{post_title}\".\n\nAfter review, we've decided not to publish this content at this time.\n\nFeel free to submit another post in the future.\n\nRegards,\n{site_name}"
        );
        
        add_option('igpr_settings', $default_settings);
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Cleanup if needed
    }
}

// Initialize the plugin
new Instant_Guest_Post_Request();
