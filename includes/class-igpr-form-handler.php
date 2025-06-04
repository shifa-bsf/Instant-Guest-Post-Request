<?php
/**
 * Form Handler Class
 * 
 * Handles the frontend form display and submission
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IGPR_Form_Handler {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Register shortcode
        add_shortcode('guest_post_form', array($this, 'render_form'));
        
        // Register scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
        
        // Handle form submission
        add_action('init', array($this, 'handle_form_submission'));
    }
    
    /**
     * Register scripts and styles
     */
    public function register_scripts() {
        // Only load on pages with our shortcode
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'guest_post_form')) {
            // Enqueue WordPress media uploader scripts
            wp_enqueue_media();
            
            // Enqueue dashicons for tooltips
            wp_enqueue_style('dashicons');
            
            // Enqueue our custom CSS
            wp_enqueue_style('igpr-form-style', IGPR_PLUGIN_URL . 'assets/css/form.css', array(), IGPR_VERSION);
            
            // Enqueue our custom JS
            wp_enqueue_script('igpr-form-script', IGPR_PLUGIN_URL . 'assets/js/form.js', array('jquery'), IGPR_VERSION, true);
            
            // Localize script with nonce and translations
            wp_localize_script('igpr-form-script', 'igpr_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('igpr_form_nonce'),
                'dark_theme_text' => __('Dark Mode', 'instant-guest-post-request'),
                'light_theme_text' => __('Light Mode', 'instant-guest-post-request')
            ));
        }
    }
    
    /**
     * Render the guest post submission form
     */
    public function render_form() {
        // Check if user has exceeded submission limit
        $settings = get_option('igpr_settings');
        $submission_limit = isset($settings['submission_limit']) ? intval($settings['submission_limit']) : 3;
        
        if ($submission_limit > 0) {
            $user_ip = $this->get_user_ip();
            $submissions_count = $this->get_submissions_count($user_ip);
            
            if ($submissions_count >= $submission_limit) {
                return '<div class="igpr-form-message igpr-error">' . 
                    __('You have reached the maximum number of submissions allowed.', 'instant-guest-post-request') . 
                    '</div>';
            }
        }
        
        // Start output buffering
        ob_start();
        
        // Check for form messages
        if (isset($_GET['igpr_message']) && $_GET['igpr_message'] == 'success') {
            echo '<div class="igpr-form-message igpr-success">' . 
                __('Thank you! Your guest post has been submitted for review.', 'instant-guest-post-request') . 
                '</div>';
        }
        
        // Get default theme setting
        $default_theme = isset($settings['default_theme']) ? $settings['default_theme'] : 'light';
        $form_class = ($default_theme === 'dark') ? 'igpr-form-container dark-theme' : 'igpr-form-container';
        
        // Include form template with theme class
        include IGPR_PLUGIN_DIR . 'templates/form-template.php';
        
        // Return the buffered content
        return ob_get_clean();
    }
    
    /**
     * Handle form submission
     */
    public function handle_form_submission() {
        // Check if form is submitted
        if (!isset($_POST['igpr_submit_post']) || !wp_verify_nonce($_POST['igpr_nonce'], 'igpr_form_nonce')) {
            return;
        }
        
        // Validate required fields
        $errors = array();
        
        if (empty($_POST['igpr_post_title'])) {
            $errors[] = __('Post title is required.', 'instant-guest-post-request');
        }
        
        if (empty($_POST['igpr_post_content'])) {
            $errors[] = __('Post content is required.', 'instant-guest-post-request');
        }
        
        if (empty($_POST['igpr_author_name'])) {
            $errors[] = __('Author name is required.', 'instant-guest-post-request');
        }
        
        if (empty($_POST['igpr_author_email']) || !is_email($_POST['igpr_author_email'])) {
            $errors[] = __('A valid email address is required.', 'instant-guest-post-request');
        }
        
        // If there are errors, redirect back with error messages
        if (!empty($errors)) {
            set_transient('igpr_form_errors', $errors, 60 * 5); // Store errors for 5 minutes
            wp_redirect(wp_get_referer());
            exit;
        }
        
        // Sanitize input data
        $post_data = array(
            'post_title' => sanitize_text_field($_POST['igpr_post_title']),
            'post_content' => wp_kses_post($_POST['igpr_post_content']),
            'author_name' => sanitize_text_field($_POST['igpr_author_name']),
            'author_email' => sanitize_email($_POST['igpr_author_email']),
            'author_bio' => sanitize_textarea_field($_POST['igpr_author_bio']),
            'featured_image' => isset($_POST['igpr_featured_image']) ? intval($_POST['igpr_featured_image']) : 0
        );
        
        // Pass data to post handler
        $post_handler = new IGPR_Post_Handler();
        $post_id = $post_handler->create_guest_post($post_data);
        
        if ($post_id) {
            // Store user IP for submission limit tracking
            $this->record_submission();
            
            // Send notification to admin
            $notification = new IGPR_Notification();
            $notification->send_admin_notification($post_id, $post_data);
            
            // Redirect to success page
            wp_redirect(add_query_arg('igpr_message', 'success', wp_get_referer()));
            exit;
        } else {
            // Handle error
            set_transient('igpr_form_errors', array(__('An error occurred while submitting your post. Please try again.', 'instant-guest-post-request')), 60 * 5);
            wp_redirect(wp_get_referer());
            exit;
        }
    }
    
    /**
     * Get user IP address
     */
    private function get_user_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    
    /**
     * Record user submission
     */
    private function record_submission() {
        $ip = $this->get_user_ip();
        $submissions = get_option('igpr_submissions', array());
        
        if (!isset($submissions[$ip])) {
            $submissions[$ip] = array();
        }
        
        $submissions[$ip][] = time();
        
        // Clean up old submissions (older than 30 days)
        foreach ($submissions as $user_ip => $timestamps) {
            $submissions[$user_ip] = array_filter($timestamps, function($timestamp) {
                return $timestamp > (time() - (30 * DAY_IN_SECONDS));
            });
            
            // Remove empty entries
            if (empty($submissions[$user_ip])) {
                unset($submissions[$user_ip]);
            }
        }
        
        update_option('igpr_submissions', $submissions);
    }
    
    /**
     * Get number of submissions from an IP
     */
    private function get_submissions_count($ip) {
        $submissions = get_option('igpr_submissions', array());
        
        if (!isset($submissions[$ip])) {
            return 0;
        }
        
        // Count submissions in the last 24 hours
        $recent_submissions = array_filter($submissions[$ip], function($timestamp) {
            return $timestamp > (time() - DAY_IN_SECONDS);
        });
        
        return count($recent_submissions);
    }
}
