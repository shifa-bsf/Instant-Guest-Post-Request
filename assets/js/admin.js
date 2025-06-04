/**
 * Admin Settings JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Tabs functionality
        $('.igpr-tabs-nav a').on('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            $('.igpr-tabs-nav a').removeClass('active');
            $('.igpr-tab-content').removeClass('active');
            
            // Add active class to current tab
            $(this).addClass('active');
            
            // Show corresponding tab content
            var target = $(this).attr('href');
            $(target).addClass('active');
            
            // Save active tab to localStorage
            localStorage.setItem('igpr_active_tab', target);
        });
        
        // Check for saved active tab
        var savedTab = localStorage.getItem('igpr_active_tab');
        if (savedTab && $(savedTab).length) {
            $('.igpr-tabs-nav a[href="' + savedTab + '"]').trigger('click');
        } else {
            // Default to first tab
            $('.igpr-tabs-nav a:first').trigger('click');
        }
        
        // Toggle fields based on moderation setting
        $('#igpr_moderation').on('change', function() {
            if ($(this).is(':checked')) {
                $('.igpr-moderation-dependent').show();
            } else {
                $('.igpr-moderation-dependent').hide();
            }
        }).trigger('change');
        
        // Toggle auto-reply fields
        $('#igpr_auto_reply').on('change', function() {
            if ($(this).is(':checked')) {
                $('.igpr-auto-reply-fields').show();
            } else {
                $('.igpr-auto-reply-fields').hide();
            }
        }).trigger('change');
    });
})(jQuery);
