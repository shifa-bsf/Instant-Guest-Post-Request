/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import FormField from '../common/FormField';

const GeneralTab = ({ settings, onChange, categories, loading }) => {
  if (loading) {
    return <div className="igpr-text-center igpr-py-4">{__('Loading...', 'instant-guest-post-request')}</div>;
  }

  const categoryOptions = [
    { value: '0', label: __('Select a category', 'instant-guest-post-request') },
    ...categories.map(category => ({
      value: category.id.toString(),
      label: category.name
    }))
  ];

  return (
    <div>
      <h2 className="igpr-text-lg igpr-font-medium igpr-text-gray-900 igpr-mb-4">
        {__('General Settings', 'instant-guest-post-request')}
      </h2>
      
      <p className="igpr-mb-4 igpr-text-gray-600">
        {__('Configure general settings for guest post submissions.', 'instant-guest-post-request')}
      </p>
      
      <FormField
        label={__('Default Category', 'instant-guest-post-request')}
        id="default_category"
        type="select"
        value={settings.default_category || '0'}
        onChange={(value) => onChange('default_category', value)}
        options={categoryOptions}
        tooltip={__('Select the default category for guest post submissions.', 'instant-guest-post-request')}
      />
      
      <FormField
        label={__('Require Moderation', 'instant-guest-post-request')}
        id="moderation"
        type="checkbox"
        value={settings.moderation === 'on'}
        onChange={(value) => onChange('moderation', value ? 'on' : 'off')}
        tooltip={__('If checked, guest posts will be set to pending status and require approval before publishing.', 'instant-guest-post-request')}
      />
      
      <FormField
        label={__('Submission Limit per IP (24 hours)', 'instant-guest-post-request')}
        id="submission_limit"
        type="text"
        value={settings.submission_limit || '3'}
        onChange={(value) => onChange('submission_limit', value)}
        tooltip={__('Maximum number of submissions allowed from the same IP address within a 24-hour period. Set to 0 for unlimited.', 'instant-guest-post-request')}
      />
    </div>
  );
};

export default GeneralTab;
