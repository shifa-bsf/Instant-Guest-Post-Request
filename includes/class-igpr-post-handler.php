<?php
/**
 * Post Handler Class
 * 
 * Handles the creation and management of guest posts
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IGPR_Post_Handler {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Register custom post status
        add_action('init', array($this, 'register_post_status'));
        
        // Add meta boxes for guest post details
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        
        // Save meta box data
        add_action('save_post', array($this, 'save_meta_box_data'));
        
        // Add custom column to posts list
        add_filter('manage_posts_columns', array($this, 'add_guest_post_column'));
        add_action('manage_posts_custom_column', array($this, 'display_guest_post_column'), 10, 2);
        
        // Add bulk actions
        add_filter('bulk_actions-edit-post', array($this, 'add_bulk_actions'));
        add_filter('handle_bulk_actions-edit-post', array($this, 'handle_bulk_actions'), 10, 3);
        
        // Handle approve/reject actions
        add_action('admin_init', array($this, 'handle_post_actions'));
    }
    
    /**
     * Register custom post status
     */
    public function register_post_status() {
        register_post_status('guest-pending', array(
            'label' => _x('Guest Pending', 'post status', 'instant-guest-post-request'),
            'public' => false,
            'exclude_from_search' => true,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Guest Pending <span class="count">(%s)</span>', 'Guest Pending <span class="count">(%s)</span>', 'instant-guest-post-request')
        ));
    }
    
    /**
     * Create a guest post from form submission
     */
    public function create_guest_post($post_data) {
        // Get settings
        $settings = get_option('igpr_settings');
        $default_category = isset($settings['default_category']) ? intval($settings['default_category']) : get_option('default_category');
        $moderation = isset($settings['moderation']) ? $settings['moderation'] : 'on';
        
        // Create post array
        $post_arr = array(
            'post_title' => $post_data['post_title'],
            'post_content' => $post_data['post_content'],
            'post_status' => ($moderation == 'on') ? 'pending' : 'publish',
            'post_author' => 1, // Default admin user
            'post_category' => array($default_category),
            'post_type' => 'post'
        );
        
        // Insert the post
        $post_id = wp_insert_post($post_arr);
        
        if (!is_wp_error($post_id)) {
            // Save author meta data
            update_post_meta($post_id, '_igpr_author_name', $post_data['author_name']);
            update_post_meta($post_id, '_igpr_author_email', $post_data['author_email']);
            update_post_meta($post_id, '_igpr_author_bio', $post_data['author_bio']);
            update_post_meta($post_id, '_igpr_is_guest_post', 'yes');
            
            // Set featured image if provided
            if (!empty($post_data['featured_image'])) {
                set_post_thumbnail($post_id, $post_data['featured_image']);
            }
            
            // Send auto-reply if enabled
            $notification = new IGPR_Notification();
            $notification->send_auto_reply($post_id, $post_data);
            
            return $post_id;
        }
        
        return false;
    }
    
    /**
     * Add meta boxes for guest post details
     */
    public function add_meta_boxes() {
        add_meta_box(
            'igpr_guest_post_details',
            __('Guest Author Details', 'instant-guest-post-request'),
            array($this, 'render_meta_box'),
            'post',
            'side',
            'high'
        );
    }
    
    /**
     * Render meta box content
     */
    public function render_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('igpr_meta_box', 'igpr_meta_box_nonce');
        
        // Get saved values
        $is_guest_post = get_post_meta($post->ID, '_igpr_is_guest_post', true);
        
        if ($is_guest_post != 'yes') {
            echo '<p>' . __('This is not a guest post.', 'instant-guest-post-request') . '</p>';
            return;
        }
        
        $author_name = get_post_meta($post->ID, '_igpr_author_name', true);
        $author_email = get_post_meta($post->ID, '_igpr_author_email', true);
        $author_bio = get_post_meta($post->ID, '_igpr_author_bio', true);
        
        // Output fields
        ?>
        <p>
            <label for="igpr_author_name"><?php _e('Author Name:', 'instant-guest-post-request'); ?></label>
            <input type="text" id="igpr_author_name" name="igpr_author_name" value="<?php echo esc_attr($author_name); ?>" class="widefat">
        </p>
        <p>
            <label for="igpr_author_email"><?php _e('Author Email:', 'instant-guest-post-request'); ?></label>
            <input type="email" id="igpr_author_email" name="igpr_author_email" value="<?php echo esc_attr($author_email); ?>" class="widefat">
        </p>
        <p>
            <label for="igpr_author_bio"><?php _e('Author Bio:', 'instant-guest-post-request'); ?></label>
            <textarea id="igpr_author_bio" name="igpr_author_bio" class="widefat" rows="4"><?php echo esc_textarea($author_bio); ?></textarea>
        </p>
        <?php
    }
    
    /**
     * Save meta box data
     */
    public function save_meta_box_data($post_id) {
        // Check if nonce is set
        if (!isset($_POST['igpr_meta_box_nonce'])) {
            return;
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['igpr_meta_box_nonce'], 'igpr_meta_box')) {
            return;
        }
        
        // If this is an autosave, don't do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Check if this is a guest post
        $is_guest_post = get_post_meta($post_id, '_igpr_is_guest_post', true);
        if ($is_guest_post != 'yes') {
            return;
        }
        
        // Save author data
        if (isset($_POST['igpr_author_name'])) {
            update_post_meta($post_id, '_igpr_author_name', sanitize_text_field($_POST['igpr_author_name']));
        }
        
        if (isset($_POST['igpr_author_email'])) {
            update_post_meta($post_id, '_igpr_author_email', sanitize_email($_POST['igpr_author_email']));
        }
        
        if (isset($_POST['igpr_author_bio'])) {
            update_post_meta($post_id, '_igpr_author_bio', sanitize_textarea_field($_POST['igpr_author_bio']));
        }
    }
    
    /**
     * Add custom column to posts list
     */
    public function add_guest_post_column($columns) {
        $columns['guest_post'] = __('Guest Post', 'instant-guest-post-request');
        return $columns;
    }
    
    /**
     * Display guest post column content
     */
    public function display_guest_post_column($column, $post_id) {
        if ($column == 'guest_post') {
            $is_guest_post = get_post_meta($post_id, '_igpr_is_guest_post', true);
            if ($is_guest_post == 'yes') {
                $author_name = get_post_meta($post_id, '_igpr_author_name', true);
                echo '<span class="dashicons dashicons-businessman" title="' . esc_attr__('Guest Post by', 'instant-guest-post-request') . ' ' . esc_attr($author_name) . '"></span> ' . esc_html($author_name);
            }
        }
    }
    
    /**
     * Add bulk actions for guest posts
     */
    public function add_bulk_actions($actions) {
        $actions['approve_guest_posts'] = __('Approve Guest Posts', 'instant-guest-post-request');
        $actions['reject_guest_posts'] = __('Reject Guest Posts', 'instant-guest-post-request');
        return $actions;
    }
    
    /**
     * Handle bulk actions
     */
    public function handle_bulk_actions($redirect_to, $action, $post_ids) {
        if ($action !== 'approve_guest_posts' && $action !== 'reject_guest_posts') {
            return $redirect_to;
        }
        
        $notification = new IGPR_Notification();
        $processed = 0;
        
        foreach ($post_ids as $post_id) {
            $is_guest_post = get_post_meta($post_id, '_igpr_is_guest_post', true);
            
            if ($is_guest_post == 'yes') {
                if ($action === 'approve_guest_posts') {
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_status' => 'publish'
                    ));
                    
                    $notification->send_approval_notification($post_id);
                    $processed++;
                } elseif ($action === 'reject_guest_posts') {
                    wp_update_post(array(
                        'ID' => $post_id,
                        'post_status' => 'trash'
                    ));
                    
                    $notification->send_rejection_notification($post_id);
                    $processed++;
                }
            }
        }
        
        return add_query_arg('processed_count', $processed, $redirect_to);
    }
    
    /**
     * Handle approve/reject actions from admin notification email
     */
    public function handle_post_actions() {
        if (!isset($_GET['igpr_action']) || !isset($_GET['post_id']) || !isset($_GET['token'])) {
            return;
        }
        
        $action = sanitize_text_field($_GET['igpr_action']);
        $post_id = intval($_GET['post_id']);
        $token = sanitize_text_field($_GET['token']);
        
        // Verify token
        $expected_token = md5('igpr_' . $post_id . get_option('site_url'));
        if ($token !== $expected_token) {
            wp_die(__('Invalid security token.', 'instant-guest-post-request'));
        }
        
        // Check if post exists and is a guest post
        $post = get_post($post_id);
        if (!$post || get_post_meta($post_id, '_igpr_is_guest_post', true) !== 'yes') {
            wp_die(__('Invalid post.', 'instant-guest-post-request'));
        }
        
        $notification = new IGPR_Notification();
        
        if ($action === 'approve') {
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'publish'
            ));
            
            $notification->send_approval_notification($post_id);
            
            wp_redirect(admin_url('edit.php?igpr_message=approved'));
            exit;
        } elseif ($action === 'reject') {
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'trash'
            ));
            
            $notification->send_rejection_notification($post_id);
            
            wp_redirect(admin_url('edit.php?igpr_message=rejected'));
            exit;
        }
    }
}
