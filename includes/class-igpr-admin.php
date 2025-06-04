<?php
/**
 * Admin Class
 * 
 * Handles the admin functionality
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IGPR_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Enqueue admin scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('Guest Post Plugin', 'instant-guest-post-request'),
            __('Guest Post Plugin', 'instant-guest-post-request'),
            'manage_options',
            'igpr-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook != 'settings_page_igpr-settings') {
            return;
        }
        
        // Enqueue React app
        wp_enqueue_script(
            'igpr-admin-script',
            IGPR_PLUGIN_URL . 'build/admin.js',
            array('wp-element', 'wp-components', 'wp-api-fetch', 'wp-i18n'),
            IGPR_VERSION,
            true
        );
        
        // Enqueue admin styles
        wp_enqueue_style(
            'igpr-admin-style',
            IGPR_PLUGIN_URL . 'build/admin.css',
            array(),
            IGPR_VERSION
        );
        
        // Add nonce for REST API
        wp_localize_script('igpr-admin-script', 'igprData', array(
            'nonce' => wp_create_nonce('wp_rest'),
            'restUrl' => esc_url_raw(rest_url()),
        ));
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <div id="igpr-react-admin-app"></div>
        </div>
        <?php
    }
}
