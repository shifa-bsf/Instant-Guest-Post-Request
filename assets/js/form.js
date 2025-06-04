/**
 * Guest Post Form JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Theme toggle functionality
        $('#igpr_theme_toggle').on('click', function() {
            var formContainer = $('.igpr-form-container');
            formContainer.toggleClass('dark-theme');
            
            // Update button text and icon
            if (formContainer.hasClass('dark-theme')) {
                $(this).html('<span class="theme-icon">☀️</span> ' + igpr_vars.light_theme_text);
                // Store preference in localStorage
                localStorage.setItem('igpr_theme', 'dark');
            } else {
                $(this).html('<span class="theme-icon">🌙</span> ' + igpr_vars.dark_theme_text);
                localStorage.setItem('igpr_theme', 'light');
            }
        });
        
        // Check for saved theme preference
        var savedTheme = localStorage.getItem('igpr_theme');
        if (savedTheme === 'dark') {
            $('.igpr-form-container').addClass('dark-theme');
            $('#igpr_theme_toggle').html('<span class="theme-icon">☀️</span> ' + igpr_vars.light_theme_text);
        }
        
        // Featured image upload
        var mediaUploader;
        
        $('#igpr_featured_image_button').on('click', function(e) {
            e.preventDefault();
            
            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Select Featured Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });
            
            // When an image is selected, run a callback
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#igpr_featured_image').val(attachment.id);
                
                // Display the image preview
                var imgPreview = '<img src="' + attachment.url + '" alt="Featured Image">';
                $('#igpr_featured_image_preview').html(imgPreview);
                
                // Show remove button
                $('#igpr_featured_image_remove').show();
            });
            
            // Open the uploader dialog
            mediaUploader.open();
        });
        
        // Remove featured image
        $('#igpr_featured_image_remove').on('click', function(e) {
            e.preventDefault();
            
            // Clear the image ID
            $('#igpr_featured_image').val('');
            
            // Clear the preview
            $('#igpr_featured_image_preview').html('');
            
            // Hide remove button
            $(this).hide();
        });
        
        // Form validation
        $('#igpr-guest-post-form').on('submit', function(e) {
            var valid = true;
            var errorMessages = [];
            
            // Check required fields
            if ($('#igpr_post_title').val().trim() === '') {
                errorMessages.push('Post title is required.');
                valid = false;
            }
            
            // Check if TinyMCE is active
            var content = '';
            if (typeof tinyMCE !== 'undefined' && tinyMCE.get('igpr_post_content') !== null) {
                content = tinyMCE.get('igpr_post_content').getContent();
            } else {
                content = $('#igpr_post_content').val();
            }
            
            if (content.trim() === '') {
                errorMessages.push('Post content is required.');
                valid = false;
            }
            
            if ($('#igpr_author_name').val().trim() === '') {
                errorMessages.push('Author name is required.');
                valid = false;
            }
            
            var email = $('#igpr_author_email').val().trim();
            if (email === '') {
                errorMessages.push('Email address is required.');
                valid = false;
            } else if (!isValidEmail(email)) {
                errorMessages.push('Please enter a valid email address.');
                valid = false;
            }
            
            // Display error messages if any
            if (!valid) {
                e.preventDefault();
                
                // Create error message container if it doesn't exist
                if ($('.igpr-form-message.igpr-error').length === 0) {
                    $('<div class="igpr-form-message igpr-error"></div>').insertBefore('#igpr-guest-post-form');
                }
                
                // Add error messages
                var errorHtml = '';
                $.each(errorMessages, function(index, message) {
                    errorHtml += '<p>' + message + '</p>';
                });
                
                $('.igpr-form-message.igpr-error').html(errorHtml);
                
                // Scroll to error messages
                $('html, body').animate({
                    scrollTop: $('.igpr-form-message.igpr-error').offset().top - 100
                }, 500);
            }
        });
        
        // Helper function to validate email
        function isValidEmail(email) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return pattern.test(email);
        }
    });
})(jQuery);
