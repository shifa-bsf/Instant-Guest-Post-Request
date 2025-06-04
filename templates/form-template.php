<?php
/**
 * Guest Post Form Template
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Display any errors
$errors = get_transient('igpr_form_errors');
if ($errors) {
    echo '<div class="igpr-form-message igpr-error">';
    foreach ($errors as $error) {
        echo '<p>' . esc_html($error) . '</p>';
    }
    echo '</div>';
    delete_transient('igpr_form_errors');
}
?>

<div class="igpr-form-container">
    <div class="igpr-theme-toggle">
        <button type="button" id="igpr_theme_toggle"><span class="theme-icon">🌙</span> <?php _e('Dark Mode', 'instant-guest-post-request'); ?></button>
    </div>
    
    <form id="igpr-guest-post-form" class="igpr-form" method="post" enctype="multipart/form-data">
        <?php wp_nonce_field('igpr_form_nonce', 'igpr_nonce'); ?>
        
        <div class="igpr-form-field">
            <label for="igpr_post_title">
                <?php _e('Post Title', 'instant-guest-post-request'); ?> <span class="required">*</span>
                <span class="igpr-tooltip">
                    <span class="dashicons dashicons-editor-help"></span>
                    <span class="igpr-tooltip-text"><?php _e('Enter a compelling title for your guest post.', 'instant-guest-post-request'); ?></span>
                </span>
            </label>
            <input type="text" id="igpr_post_title" name="igpr_post_title" required>
        </div>
        
        <div class="igpr-form-field">
            <label for="igpr_post_content">
                <?php _e('Post Content', 'instant-guest-post-request'); ?> <span class="required">*</span>
                <span class="igpr-tooltip">
                    <span class="dashicons dashicons-editor-help"></span>
                    <span class="igpr-tooltip-text"><?php _e('Write your post content here. You can format text, add links, and insert images.', 'instant-guest-post-request'); ?></span>
                </span>
            </label>
            <?php
            // Use WordPress editor
            $content = '';
            $editor_id = 'igpr_post_content';
            $settings = array(
                'textarea_name' => 'igpr_post_content',
                'textarea_rows' => 10,
                'media_buttons' => false,
                'teeny' => true,
                'quicktags' => true
            );
            wp_editor($content, $editor_id, $settings);
            ?>
        </div>
        
        <div class="igpr-form-field">
            <label for="igpr_author_name">
                <?php _e('Author Name', 'instant-guest-post-request'); ?> <span class="required">*</span>
                <span class="igpr-tooltip">
                    <span class="dashicons dashicons-editor-help"></span>
                    <span class="igpr-tooltip-text"><?php _e('Your full name as you want it to appear with your post.', 'instant-guest-post-request'); ?></span>
                </span>
            </label>
            <input type="text" id="igpr_author_name" name="igpr_author_name" required>
        </div>
        
        <div class="igpr-form-field">
            <label for="igpr_author_email">
                <?php _e('Email Address', 'instant-guest-post-request'); ?> <span class="required">*</span>
                <span class="igpr-tooltip">
                    <span class="dashicons dashicons-editor-help"></span>
                    <span class="igpr-tooltip-text"><?php _e('Your email address (will not be published). We\'ll use this to notify you about your submission.', 'instant-guest-post-request'); ?></span>
                </span>
            </label>
            <input type="email" id="igpr_author_email" name="igpr_author_email" required>
        </div>
        
        <div class="igpr-form-field">
            <label for="igpr_author_bio">
                <?php _e('Author Bio', 'instant-guest-post-request'); ?>
                <span class="igpr-tooltip">
                    <span class="dashicons dashicons-editor-help"></span>
                    <span class="igpr-tooltip-text"><?php _e('A short biography about yourself. This may be displayed with your post.', 'instant-guest-post-request'); ?></span>
                </span>
            </label>
            <textarea id="igpr_author_bio" name="igpr_author_bio" rows="4"></textarea>
        </div>
        
        <div class="igpr-form-field">
            <label for="igpr_featured_image_button">
                <?php _e('Featured Image', 'instant-guest-post-request'); ?>
                <span class="igpr-tooltip">
                    <span class="dashicons dashicons-editor-help"></span>
                    <span class="igpr-tooltip-text"><?php _e('Upload an image to be used as the featured image for your post.', 'instant-guest-post-request'); ?></span>
                </span>
            </label>
            <div class="igpr-featured-image-container">
                <div id="igpr_featured_image_preview" class="igpr-featured-image-preview"></div>
                <input type="hidden" id="igpr_featured_image" name="igpr_featured_image" value="">
                <button type="button" id="igpr_featured_image_button" class="button"><?php _e('Select Image', 'instant-guest-post-request'); ?></button>
                <button type="button" id="igpr_featured_image_remove" class="button" style="display:none;"><?php _e('Remove Image', 'instant-guest-post-request'); ?></button>
            </div>
        </div>
        
        <div class="igpr-form-submit">
            <input type="submit" name="igpr_submit_post" value="<?php _e('Submit Guest Post', 'instant-guest-post-request'); ?>" class="button button-primary">
        </div>
    </form>
</div>
