<?php
/**
 * Notification Class
 * 
 * Handles email notifications for guest posts
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IGPR_Notification {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Nothing to initialize
    }
    
    /**
     * Send notification to admin about new guest post
     */
    public function send_admin_notification($post_id, $post_data) {
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        
        // Generate security token for approve/reject links
        $token = md5('igpr_' . $post_id . get_option('site_url'));
        
        // Build approve/reject URLs
        $approve_url = admin_url('admin.php?igpr_action=approve&post_id=' . $post_id . '&token=' . $token);
        $reject_url = admin_url('admin.php?igpr_action=reject&post_id=' . $post_id . '&token=' . $token);
        
        // Build preview URL
        $preview_url = add_query_arg('preview', 'true', get_permalink($post_id));
        
        // Build admin URL
        $admin_url = admin_url('post.php?post=' . $post_id . '&action=edit');
        
        // Get settings
        $settings = get_option('igpr_settings');
        
        // Email subject
        $subject = isset($settings['admin_email_subject']) ? $settings['admin_email_subject'] : __('New Guest Post Submission: "{post_title}"', 'instant-guest-post-request');
        $subject = str_replace('{post_title}', $post_data['post_title'], $subject);
        
        // Email message
        if (isset($settings['admin_email_template']) && !empty($settings['admin_email_template'])) {
            $message = $this->replace_placeholders($settings['admin_email_template'], array(
                'post_title' => $post_data['post_title'],
                'author_name' => $post_data['author_name'],
                'author_email' => $post_data['author_email'],
                'preview_url' => $preview_url,
                'approve_url' => $approve_url,
                'reject_url' => $reject_url,
                'admin_url' => $admin_url,
                'site_name' => $site_name
            ));
        } else {
            // Default template
            $message = sprintf(
                __('A new guest post was submitted:

Title: %s
Submitted by: %s (%s)

Preview: %s
Approve: %s
Reject: %s

Login to review: %s', 'instant-guest-post-request'),
                $post_data['post_title'],
                $post_data['author_name'],
                $post_data['author_email'],
                $preview_url,
                $approve_url,
                $reject_url,
                $admin_url
            );
        }
        
        // Email headers
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        // Send email
        wp_mail($admin_email, $subject, $message, $headers);
    }
    
    /**
     * Send approval notification to guest author
     */
    public function send_approval_notification($post_id) {
        // Get post data
        $post = get_post($post_id);
        if (!$post) {
            return false;
        }
        
        // Get author email
        $author_email = get_post_meta($post_id, '_igpr_author_email', true);
        if (empty($author_email)) {
            return false;
        }
        
        // Get author name
        $author_name = get_post_meta($post_id, '_igpr_author_name', true);
        
        // Get settings
        $settings = get_option('igpr_settings');
        $subject = isset($settings['approve_email_subject']) ? $settings['approve_email_subject'] : __('Your guest post has been approved', 'instant-guest-post-request');
        $template = isset($settings['approve_email_template']) ? $settings['approve_email_template'] : '';
        
        if (empty($template)) {
            $template = "Hello {author_name},\n\nYour guest post \"{post_title}\" has been approved and published on our website.\n\nYou can view it here: {post_url}\n\nThank you for your contribution!\n\nRegards,\n{site_name}";
        }
        
        // Replace placeholders
        $message = $this->replace_placeholders($template, array(
            'author_name' => $author_name,
            'post_title' => $post->post_title,
            'post_url' => get_permalink($post_id),
            'site_name' => get_bloginfo('name')
        ));
        
        // Email headers
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        // Send email
        return wp_mail($author_email, $subject, $message, $headers);
    }
    
    /**
     * Send rejection notification to guest author
     */
    public function send_rejection_notification($post_id) {
        // Get post data
        $post = get_post($post_id);
        if (!$post) {
            return false;
        }
        
        // Get author email
        $author_email = get_post_meta($post_id, '_igpr_author_email', true);
        if (empty($author_email)) {
            return false;
        }
        
        // Get author name
        $author_name = get_post_meta($post_id, '_igpr_author_name', true);
        
        // Get settings
        $settings = get_option('igpr_settings');
        $subject = isset($settings['reject_email_subject']) ? $settings['reject_email_subject'] : __('Your guest post submission', 'instant-guest-post-request');
        $template = isset($settings['reject_email_template']) ? $settings['reject_email_template'] : '';
        
        if (empty($template)) {
            $template = "Hello {author_name},\n\nThank you for submitting your guest post \"{post_title}\".\n\nAfter review, we've decided not to publish this content at this time.\n\nFeel free to submit another post in the future.\n\nRegards,\n{site_name}";
        }
        
        // Replace placeholders
        $message = $this->replace_placeholders($template, array(
            'author_name' => $author_name,
            'post_title' => $post->post_title,
            'site_name' => get_bloginfo('name')
        ));
        
        // Email headers
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        // Send email
        return wp_mail($author_email, $subject, $message, $headers);
    }
    
    /**
     * Send auto-reply to guest author
     */
    public function send_auto_reply($post_id, $post_data) {
        // Get author email
        $author_email = $post_data['author_email'];
        if (empty($author_email)) {
            return false;
        }
        
        // Get author name
        $author_name = $post_data['author_name'];
        
        // Get settings
        $settings = get_option('igpr_settings');
        $auto_reply_enabled = isset($settings['auto_reply']) ? $settings['auto_reply'] : 'off';
        
        if ($auto_reply_enabled !== 'on') {
            return false;
        }
        
        $subject = isset($settings['auto_reply_subject']) ? $settings['auto_reply_subject'] : __('Thank you for your submission', 'instant-guest-post-request');
        $template = isset($settings['auto_reply_template']) ? $settings['auto_reply_template'] : '';
        
        if (empty($template)) {
            $template = "Hello {author_name},\n\nThank you for submitting your guest post \"{post_title}\" to our website.\n\nWe have received your submission and will review it shortly. We'll notify you once we've made a decision.\n\nRegards,\n{site_name}";
        }
        
        // Replace placeholders
        $message = $this->replace_placeholders($template, array(
            'author_name' => $author_name,
            'post_title' => $post_data['post_title'],
            'site_name' => get_bloginfo('name')
        ));
        
        // Email headers
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        // Send email
        return wp_mail($author_email, $subject, $message, $headers);
    }
    
    /**
     * Replace placeholders in templates
     */
    private function replace_placeholders($template, $data) {
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }
}
