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

        // Register dashboard widget
        add_action('wp_dashboard_setup', array($this, 'register_dashboard_widget'));
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

    /**
     * Register dashboard widget
     */
    public function register_dashboard_widget() {
        wp_add_dashboard_widget(
            'igpr_recent_guest_posts',
            __('Recent Guest Posts', 'instant-guest-post-request'),
            array($this, 'render_dashboard_widget')
        );
    }

    /**
     * Render dashboard widget content
     */
    public function render_dashboard_widget() {
        $recent_posts = get_posts(array(
            'post_type'      => 'post',
            'posts_per_page' => 5,
            'meta_key'       => '_igpr_is_guest_post',
            'meta_value'     => 'yes',
            'post_status'    => array('publish', 'pending'),
        ));

        if (empty($recent_posts)) {
            echo '<p>' . esc_html__( 'No recent guest posts found.', 'instant-guest-post-request' ) . '</p>';
            return;
        }

        echo '<ul class="igpr-dashboard-posts">';
        foreach ($recent_posts as $post) {
            $author = get_post_meta($post->ID, '_igpr_author_name', true);
            $status = $post->post_status === 'pending' ? __('Pending', 'instant-guest-post-request') : __('Published', 'instant-guest-post-request');
            $edit_link = get_edit_post_link($post->ID);
            echo '<li>';
            echo '<a href="' . esc_url($edit_link) . '">' . esc_html($post->post_title) . '</a>'; 
            if ($author) {
                echo ' - ' . esc_html($author);
            }
            echo ' <span class="igpr-status igpr-' . esc_attr($post->post_status) . '">' . esc_html($status) . '</span>';
            echo '</li>';
        }
        echo '</ul>';
    }
}
