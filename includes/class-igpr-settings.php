<?php
/**
 * Settings Class
 * 
 * Handles the plugin settings page
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IGPR_Settings {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Add settings page
        add_action('admin_menu', array($this, 'add_settings_page'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Enqueue admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add settings page to admin menu
     */
    public function add_settings_page() {
        add_options_page(
            __('Guest Post Plugin', 'instant-guest-post-request'),
            __('Guest Post Plugin', 'instant-guest-post-request'),
            'manage_options',
            'igpr-settings',
            array($this, 'render_settings_page')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook != 'settings_page_igpr-settings') {
            return;
        }
        
        // Enqueue dashicons for tooltips
        wp_enqueue_style('dashicons');
        
        // Enqueue admin CSS
        wp_enqueue_style('igpr-admin-style', IGPR_PLUGIN_URL . 'assets/css/admin.css', array(), IGPR_VERSION);
        
        // Enqueue admin JS
        wp_enqueue_script('igpr-admin-script', IGPR_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), IGPR_VERSION, true);
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('igpr_settings_group', 'igpr_settings', array($this, 'sanitize_settings'));
        
        // General settings section
        add_settings_section(
            'igpr_general_section',
            __('General Settings', 'instant-guest-post-request'),
            array($this, 'render_general_section'),
            'igpr-settings-general'
        );
        
        add_settings_field(
            'default_category',
            __('Default Category', 'instant-guest-post-request'),
            array($this, 'render_default_category_field'),
            'igpr-settings-general',
            'igpr_general_section'
        );
        
        add_settings_field(
            'moderation',
            __('Require Moderation', 'instant-guest-post-request'),
            array($this, 'render_moderation_field'),
            'igpr-settings-general',
            'igpr_general_section'
        );
        
        add_settings_field(
            'submission_limit',
            __('Submission Limit per IP (24 hours)', 'instant-guest-post-request'),
            array($this, 'render_submission_limit_field'),
            'igpr-settings-general',
            'igpr_general_section'
        );
        
        // Notifications section
        add_settings_section(
            'igpr_notifications_section',
            __('Notification Settings', 'instant-guest-post-request'),
            array($this, 'render_notifications_section'),
            'igpr-settings-notifications'
        );
        
        add_settings_field(
            'admin_email_subject',
            __('Admin Notification Subject', 'instant-guest-post-request'),
            array($this, 'render_admin_email_subject_field'),
            'igpr-settings-notifications',
            'igpr_notifications_section'
        );
        
        add_settings_field(
            'admin_email_template',
            __('Admin Notification Template', 'instant-guest-post-request'),
            array($this, 'render_admin_email_template_field'),
            'igpr-settings-notifications',
            'igpr_notifications_section'
        );
        
        add_settings_field(
            'auto_reply',
            __('Auto-Reply to Submitter', 'instant-guest-post-request'),
            array($this, 'render_auto_reply_field'),
            'igpr-settings-notifications',
            'igpr_notifications_section'
        );
        
        add_settings_field(
            'auto_reply_subject',
            __('Auto-Reply Email Subject', 'instant-guest-post-request'),
            array($this, 'render_auto_reply_subject_field'),
            'igpr-settings-notifications',
            'igpr_notifications_section',
            array('class' => 'igpr-auto-reply-fields')
        );
        
        add_settings_field(
            'auto_reply_template',
            __('Auto-Reply Email Template', 'instant-guest-post-request'),
            array($this, 'render_auto_reply_template_field'),
            'igpr-settings-notifications',
            'igpr_notifications_section',
            array('class' => 'igpr-auto-reply-fields')
        );
        
        add_settings_field(
            'approve_email_subject',
            __('Approval Email Subject', 'instant-guest-post-request'),
            array($this, 'render_approve_email_subject_field'),
            'igpr-settings-notifications',
            'igpr_notifications_section',
            array('class' => 'igpr-moderation-dependent')
        );
        
        add_settings_field(
            'approve_email_template',
            __('Approval Email Template', 'instant-guest-post-request'),
            array($this, 'render_approve_email_template_field'),
            'igpr-settings-notifications',
            'igpr_notifications_section',
            array('class' => 'igpr-moderation-dependent')
        );
        
        add_settings_field(
            'reject_email_subject',
            __('Rejection Email Subject', 'instant-guest-post-request'),
            array($this, 'render_reject_email_subject_field'),
            'igpr-settings-notifications',
            'igpr_notifications_section',
            array('class' => 'igpr-moderation-dependent')
        );
        
        add_settings_field(
            'reject_email_template',
            __('Rejection Email Template', 'instant-guest-post-request'),
            array($this, 'render_reject_email_template_field'),
            'igpr-settings-notifications',
            'igpr_notifications_section',
            array('class' => 'igpr-moderation-dependent')
        );
        
        // Form Style section
        add_settings_section(
            'igpr_form_style_section',
            __('Form Style Settings', 'instant-guest-post-request'),
            array($this, 'render_form_style_section'),
            'igpr-settings-form-style'
        );
        
        add_settings_field(
            'default_theme',
            __('Default Theme', 'instant-guest-post-request'),
            array($this, 'render_default_theme_field'),
            'igpr-settings-form-style',
            'igpr_form_style_section'
        );
    }
    
    /**
     * Sanitize settings
     */
    public function sanitize_settings($input) {
        $sanitized = array();
        
        // Sanitize default category
        $sanitized['default_category'] = isset($input['default_category']) ? intval($input['default_category']) : get_option('default_category');
        
        // Sanitize moderation
        $sanitized['moderation'] = isset($input['moderation']) ? 'on' : 'off';
        
        // Sanitize submission limit
        $sanitized['submission_limit'] = isset($input['submission_limit']) ? intval($input['submission_limit']) : 3;
        
        // Sanitize auto-reply
        $sanitized['auto_reply'] = isset($input['auto_reply']) ? 'on' : 'off';
        
        // Sanitize default theme
        $sanitized['default_theme'] = isset($input['default_theme']) && in_array($input['default_theme'], array('light', 'dark')) ? $input['default_theme'] : 'light';
        
        // Sanitize email subjects
        $sanitized['admin_email_subject'] = isset($input['admin_email_subject']) ? sanitize_text_field($input['admin_email_subject']) : '';
        $sanitized['auto_reply_subject'] = isset($input['auto_reply_subject']) ? sanitize_text_field($input['auto_reply_subject']) : '';
        $sanitized['approve_email_subject'] = isset($input['approve_email_subject']) ? sanitize_text_field($input['approve_email_subject']) : '';
        $sanitized['reject_email_subject'] = isset($input['reject_email_subject']) ? sanitize_text_field($input['reject_email_subject']) : '';
        
        // Sanitize email templates
        $sanitized['admin_email_template'] = isset($input['admin_email_template']) ? wp_kses_post($input['admin_email_template']) : '';
        $sanitized['auto_reply_template'] = isset($input['auto_reply_template']) ? wp_kses_post($input['auto_reply_template']) : '';
        $sanitized['approve_email_template'] = isset($input['approve_email_template']) ? wp_kses_post($input['approve_email_template']) : '';
        $sanitized['reject_email_template'] = isset($input['reject_email_template']) ? wp_kses_post($input['reject_email_template']) : '';
        
        return $sanitized;
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <ul class="igpr-tabs-nav">
                <li><a href="#igpr-tab-general"><?php _e('General', 'instant-guest-post-request'); ?></a></li>
                <li><a href="#igpr-tab-notifications"><?php _e('Notifications', 'instant-guest-post-request'); ?></a></li>
                <li><a href="#igpr-tab-form-style"><?php _e('Form Style', 'instant-guest-post-request'); ?></a></li>
            </ul>
            
            <form method="post" action="options.php">
                <?php settings_fields('igpr_settings_group'); ?>
                
                <div id="igpr-tab-general" class="igpr-tab-content">
                    <?php do_settings_sections('igpr-settings-general'); ?>
                </div>
                
                <div id="igpr-tab-notifications" class="igpr-tab-content">
                    <?php do_settings_sections('igpr-settings-notifications'); ?>
                </div>
                
                <div id="igpr-tab-form-style" class="igpr-tab-content">
                    <?php do_settings_sections('igpr-settings-form-style'); ?>
                </div>
                
                <?php submit_button(); ?>
            </form>
            
            <div class="igpr-shortcode-info">
                <h2><?php _e('Shortcode Usage', 'instant-guest-post-request'); ?></h2>
                <p><?php _e('Use the following shortcode to display the guest post submission form on any page or post:', 'instant-guest-post-request'); ?></p>
                <code>[guest_post_form]</code>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render general section description
     */
    public function render_general_section() {
        echo '<p>' . __('Configure general settings for guest post submissions.', 'instant-guest-post-request') . '</p>';
    }
    
    /**
     * Render notifications section description
     */
    public function render_notifications_section() {
        echo '<p>' . __('Configure email notifications for guest post submissions.', 'instant-guest-post-request') . '</p>';
        echo '<p>' . __('You can use the following placeholders in email templates:', 'instant-guest-post-request') . '</p>';
        echo '<ul>';
        echo '<li><code>{post_title}</code> - ' . __('The title of the submitted post', 'instant-guest-post-request') . '</li>';
        echo '<li><code>{author_name}</code> - ' . __('The name of the guest author', 'instant-guest-post-request') . '</li>';
        echo '<li><code>{author_email}</code> - ' . __('The email of the guest author', 'instant-guest-post-request') . '</li>';
        echo '<li><code>{preview_url}</code> - ' . __('URL to preview the post', 'instant-guest-post-request') . '</li>';
        echo '<li><code>{approve_url}</code> - ' . __('URL to approve the post', 'instant-guest-post-request') . '</li>';
        echo '<li><code>{reject_url}</code> - ' . __('URL to reject the post', 'instant-guest-post-request') . '</li>';
        echo '<li><code>{admin_url}</code> - ' . __('URL to edit the post in admin', 'instant-guest-post-request') . '</li>';
        echo '<li><code>{post_url}</code> - ' . __('The URL of the published post (only for approval emails)', 'instant-guest-post-request') . '</li>';
        echo '<li><code>{site_name}</code> - ' . __('Your website name', 'instant-guest-post-request') . '</li>';
        echo '</ul>';
    }
    
    /**
     * Render form style section description
     */
    public function render_form_style_section() {
        echo '<p>' . __('Configure the appearance of the guest post submission form.', 'instant-guest-post-request') . '</p>';
    }
    
    /**
     * Render default category field
     */
    public function render_default_category_field() {
        $settings = get_option('igpr_settings');
        $default_category = isset($settings['default_category']) ? $settings['default_category'] : get_option('default_category');
        
        wp_dropdown_categories(array(
            'name' => 'igpr_settings[default_category]',
            'selected' => $default_category,
            'show_option_none' => __('Select a category', 'instant-guest-post-request'),
            'option_none_value' => '0',
            'hierarchical' => true
        ));
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Select the default category for guest post submissions.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render moderation field
     */
    public function render_moderation_field() {
        $settings = get_option('igpr_settings');
        $moderation = isset($settings['moderation']) ? $settings['moderation'] : 'on';
        
        echo '<input type="checkbox" id="igpr_moderation" name="igpr_settings[moderation]" ' . checked($moderation, 'on', false) . '>';
        echo '<label for="igpr_moderation">' . __('Require moderation for guest posts', 'instant-guest-post-request') . '</label>';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('If checked, guest posts will be set to pending status and require approval before publishing.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render submission limit field
     */
    public function render_submission_limit_field() {
        $settings = get_option('igpr_settings');
        $submission_limit = isset($settings['submission_limit']) ? intval($settings['submission_limit']) : 3;
        
        echo '<input type="number" id="igpr_submission_limit" name="igpr_settings[submission_limit]" value="' . esc_attr($submission_limit) . '" min="0" step="1">';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Maximum number of submissions allowed from the same IP address within a 24-hour period. Set to 0 for unlimited.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render admin email subject field
     */
    public function render_admin_email_subject_field() {
        $settings = get_option('igpr_settings');
        $admin_email_subject = isset($settings['admin_email_subject']) ? $settings['admin_email_subject'] : __('New Guest Post Submission: "{post_title}"', 'instant-guest-post-request');
        
        echo '<input type="text" id="igpr_admin_email_subject" name="igpr_settings[admin_email_subject]" value="' . esc_attr($admin_email_subject) . '" class="regular-text">';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Subject line for the notification email sent to admin. You can use {post_title} as a placeholder.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render admin email template field
     */
    public function render_admin_email_template_field() {
        $settings = get_option('igpr_settings');
        $admin_email_template = isset($settings['admin_email_template']) ? $settings['admin_email_template'] : "A new guest post was submitted:\n\nTitle: {post_title}\nSubmitted by: {author_name} ({author_email})\n\nPreview: {preview_url}\nApprove: {approve_url}\nReject: {reject_url}\n\nLogin to review: {admin_url}";
        
        echo '<textarea id="igpr_admin_email_template" name="igpr_settings[admin_email_template]" rows="10" class="large-text">' . esc_textarea($admin_email_template) . '</textarea>';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Content of the notification email sent to admin. You can use placeholders like {post_title}, {author_name}, {author_email}, {preview_url}, {approve_url}, {reject_url}, and {admin_url}.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render auto-reply field
     */
    public function render_auto_reply_field() {
        $settings = get_option('igpr_settings');
        $auto_reply = isset($settings['auto_reply']) ? $settings['auto_reply'] : 'off';
        
        echo '<input type="checkbox" id="igpr_auto_reply" name="igpr_settings[auto_reply]" ' . checked($auto_reply, 'on', false) . '>';
        echo '<label for="igpr_auto_reply">' . __('Send auto-reply email to submitter', 'instant-guest-post-request') . '</label>';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('If checked, an automatic confirmation email will be sent to the submitter after their post is received.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render auto-reply subject field
     */
    public function render_auto_reply_subject_field() {
        $settings = get_option('igpr_settings');
        $auto_reply_subject = isset($settings['auto_reply_subject']) ? $settings['auto_reply_subject'] : __('Thank you for your submission', 'instant-guest-post-request');
        
        echo '<input type="text" id="igpr_auto_reply_subject" name="igpr_settings[auto_reply_subject]" value="' . esc_attr($auto_reply_subject) . '" class="regular-text">';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Subject line for the auto-reply email sent to submitters.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render auto-reply template field
     */
    public function render_auto_reply_template_field() {
        $settings = get_option('igpr_settings');
        $auto_reply_template = isset($settings['auto_reply_template']) ? $settings['auto_reply_template'] : "Hello {author_name},\n\nThank you for submitting your guest post \"{post_title}\" to our website.\n\nWe have received your submission and will review it shortly. We'll notify you once we've made a decision.\n\nRegards,\n{site_name}";
        
        echo '<textarea id="igpr_auto_reply_template" name="igpr_settings[auto_reply_template]" rows="10" class="large-text">' . esc_textarea($auto_reply_template) . '</textarea>';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Content of the auto-reply email sent to submitters. You can use placeholders like {author_name}, {post_title}, and {site_name}.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render approve email subject field
     */
    public function render_approve_email_subject_field() {
        $settings = get_option('igpr_settings');
        $approve_email_subject = isset($settings['approve_email_subject']) ? $settings['approve_email_subject'] : __('Your guest post has been approved', 'instant-guest-post-request');
        
        echo '<input type="text" id="igpr_approve_email_subject" name="igpr_settings[approve_email_subject]" value="' . esc_attr($approve_email_subject) . '" class="regular-text">';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Subject line for the approval notification email.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render approve email template field
     */
    public function render_approve_email_template_field() {
        $settings = get_option('igpr_settings');
        $approve_email_template = isset($settings['approve_email_template']) ? $settings['approve_email_template'] : "Hello {author_name},\n\nYour guest post \"{post_title}\" has been approved and published on our website.\n\nYou can view it here: {post_url}\n\nThank you for your contribution!\n\nRegards,\n{site_name}";
        
        echo '<textarea id="igpr_approve_email_template" name="igpr_settings[approve_email_template]" rows="10" class="large-text">' . esc_textarea($approve_email_template) . '</textarea>';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Content of the approval notification email. You can use placeholders like {author_name}, {post_title}, {post_url}, and {site_name}.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render reject email subject field
     */
    public function render_reject_email_subject_field() {
        $settings = get_option('igpr_settings');
        $reject_email_subject = isset($settings['reject_email_subject']) ? $settings['reject_email_subject'] : __('Your guest post submission', 'instant-guest-post-request');
        
        echo '<input type="text" id="igpr_reject_email_subject" name="igpr_settings[reject_email_subject]" value="' . esc_attr($reject_email_subject) . '" class="regular-text">';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Subject line for the rejection notification email.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render reject email template field
     */
    public function render_reject_email_template_field() {
        $settings = get_option('igpr_settings');
        $reject_email_template = isset($settings['reject_email_template']) ? $settings['reject_email_template'] : "Hello {author_name},\n\nThank you for submitting your guest post \"{post_title}\".\n\nAfter review, we've decided not to publish this content at this time.\n\nFeel free to submit another post in the future.\n\nRegards,\n{site_name}";
        
        echo '<textarea id="igpr_reject_email_template" name="igpr_settings[reject_email_template]" rows="10" class="large-text">' . esc_textarea($reject_email_template) . '</textarea>';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Content of the rejection notification email. You can use placeholders like {author_name}, {post_title}, and {site_name}.', 'instant-guest-post-request') . '</span>
              </span>';
    }
    
    /**
     * Render default theme field
     */
    public function render_default_theme_field() {
        $settings = get_option('igpr_settings');
        $default_theme = isset($settings['default_theme']) ? $settings['default_theme'] : 'light';
        
        echo '<select id="igpr_default_theme" name="igpr_settings[default_theme]">';
        echo '<option value="light" ' . selected($default_theme, 'light', false) . '>' . __('Light Theme', 'instant-guest-post-request') . '</option>';
        echo '<option value="dark" ' . selected($default_theme, 'dark', false) . '>' . __('Dark Theme', 'instant-guest-post-request') . '</option>';
        echo '</select>';
        
        echo '<span class="igpr-tooltip">
                <span class="dashicons dashicons-editor-help"></span>
                <span class="igpr-tooltip-text">' . __('Select the default theme for the submission form. Users can still toggle between light and dark themes.', 'instant-guest-post-request') . '</span>
              </span>';
    }
}
