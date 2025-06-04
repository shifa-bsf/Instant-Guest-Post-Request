/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import FormField from '../common/FormField';

const NotificationsTab = ({ settings, onChange, loading }) => {
  const [showAutoReplyFields, setShowAutoReplyFields] = useState(settings.auto_reply === 'on');
  
  if (loading) {
    return <div className="igpr-text-center igpr-py-4">{__('Loading...', 'instant-guest-post-request')}</div>;
  }

  const handleAutoReplyChange = (value) => {
    onChange('auto_reply', value ? 'on' : 'off');
    setShowAutoReplyFields(value);
  };

  return (
    <div>
      <h2 className="igpr-text-lg igpr-font-medium igpr-text-gray-900 igpr-mb-4">
        {__('Notification Settings', 'instant-guest-post-request')}
      </h2>
      
      <p className="igpr-mb-4 igpr-text-gray-600">
        {__('Configure email notifications for guest post submissions.', 'instant-guest-post-request')}
      </p>
      
      <div className="igpr-mb-6 igpr-p-4 igpr-bg-blue-50 igpr-rounded-md">
        <h3 className="igpr-text-sm igpr-font-medium igpr-text-blue-800 igpr-mb-2">
          {__('Available Placeholders', 'instant-guest-post-request')}
        </h3>
        <ul className="igpr-text-xs igpr-text-blue-700 igpr-list-disc igpr-pl-5 igpr-space-y-1">
          <li><code>{'{post_title}'}</code> - {__('The title of the submitted post', 'instant-guest-post-request')}</li>
          <li><code>{'{author_name}'}</code> - {__('The name of the guest author', 'instant-guest-post-request')}</li>
          <li><code>{'{author_email}'}</code> - {__('The email of the guest author', 'instant-guest-post-request')}</li>
          <li><code>{'{preview_url}'}</code> - {__('URL to preview the post', 'instant-guest-post-request')}</li>
          <li><code>{'{approve_url}'}</code> - {__('URL to approve the post', 'instant-guest-post-request')}</li>
          <li><code>{'{reject_url}'}</code> - {__('URL to reject the post', 'instant-guest-post-request')}</li>
          <li><code>{'{admin_url}'}</code> - {__('URL to edit the post in admin', 'instant-guest-post-request')}</li>
          <li><code>{'{post_url}'}</code> - {__('The URL of the published post (only for approval emails)', 'instant-guest-post-request')}</li>
          <li><code>{'{site_name}'}</code> - {__('Your website name', 'instant-guest-post-request')}</li>
        </ul>
      </div>
      
      <div className="igpr-mb-8">
        <h3 className="igpr-text-md igpr-font-medium igpr-text-gray-900 igpr-mb-4 igpr-border-b igpr-pb-2">
          {__('Admin Notifications', 'instant-guest-post-request')}
        </h3>
        
        <FormField
          label={__('Admin Notification Subject', 'instant-guest-post-request')}
          id="admin_email_subject"
          type="text"
          value={settings.admin_email_subject || ''}
          onChange={(value) => onChange('admin_email_subject', value)}
          tooltip={__('Subject line for the notification email sent to admin. You can use {post_title} as a placeholder.', 'instant-guest-post-request')}
        />
        
        <FormField
          label={__('Admin Notification Template', 'instant-guest-post-request')}
          id="admin_email_template"
          type="textarea"
          value={settings.admin_email_template || ''}
          onChange={(value) => onChange('admin_email_template', value)}
          tooltip={__('Content of the notification email sent to admin.', 'instant-guest-post-request')}
          rows={8}
        />
      </div>
      
      <div className="igpr-mb-8">
        <h3 className="igpr-text-md igpr-font-medium igpr-text-gray-900 igpr-mb-4 igpr-border-b igpr-pb-2">
          {__('Auto-Reply Settings', 'instant-guest-post-request')}
        </h3>
        
        <FormField
          label={__('Send Auto-Reply to Submitter', 'instant-guest-post-request')}
          id="auto_reply"
          type="checkbox"
          value={settings.auto_reply === 'on'}
          onChange={handleAutoReplyChange}
          tooltip={__('If checked, an automatic confirmation email will be sent to the submitter after their post is received.', 'instant-guest-post-request')}
        />
        
        {showAutoReplyFields && (
          <>
            <FormField
              label={__('Auto-Reply Email Subject', 'instant-guest-post-request')}
              id="auto_reply_subject"
              type="text"
              value={settings.auto_reply_subject || ''}
              onChange={(value) => onChange('auto_reply_subject', value)}
              tooltip={__('Subject line for the auto-reply email sent to submitters.', 'instant-guest-post-request')}
              className="igpr-mt-4"
            />
            
            <FormField
              label={__('Auto-Reply Email Template', 'instant-guest-post-request')}
              id="auto_reply_template"
              type="textarea"
              value={settings.auto_reply_template || ''}
              onChange={(value) => onChange('auto_reply_template', value)}
              tooltip={__('Content of the auto-reply email sent to submitters.', 'instant-guest-post-request')}
              rows={6}
            />
          </>
        )}
      </div>
      
      <div className="igpr-mb-8">
        <h3 className="igpr-text-md igpr-font-medium igpr-text-gray-900 igpr-mb-4 igpr-border-b igpr-pb-2">
          {__('Approval/Rejection Emails', 'instant-guest-post-request')}
        </h3>
        
        <FormField
          label={__('Approval Email Subject', 'instant-guest-post-request')}
          id="approve_email_subject"
          type="text"
          value={settings.approve_email_subject || ''}
          onChange={(value) => onChange('approve_email_subject', value)}
          tooltip={__('Subject line for the approval notification email.', 'instant-guest-post-request')}
        />
        
        <FormField
          label={__('Approval Email Template', 'instant-guest-post-request')}
          id="approve_email_template"
          type="textarea"
          value={settings.approve_email_template || ''}
          onChange={(value) => onChange('approve_email_template', value)}
          tooltip={__('Content of the approval notification email.', 'instant-guest-post-request')}
          rows={6}
        />
        
        <FormField
          label={__('Rejection Email Subject', 'instant-guest-post-request')}
          id="reject_email_subject"
          type="text"
          value={settings.reject_email_subject || ''}
          onChange={(value) => onChange('reject_email_subject', value)}
          tooltip={__('Subject line for the rejection notification email.', 'instant-guest-post-request')}
        />
        
        <FormField
          label={__('Rejection Email Template', 'instant-guest-post-request')}
          id="reject_email_template"
          type="textarea"
          value={settings.reject_email_template || ''}
          onChange={(value) => onChange('reject_email_template', value)}
          tooltip={__('Content of the rejection notification email.', 'instant-guest-post-request')}
          rows={6}
        />
      </div>
    </div>
  );
};

export default NotificationsTab;
