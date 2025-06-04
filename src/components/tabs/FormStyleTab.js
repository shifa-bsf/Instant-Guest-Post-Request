/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import FormField from '../common/FormField';

const FormStyleTab = ({ settings, onChange, loading }) => {
  if (loading) {
    return <div className="igpr-text-center igpr-py-4">{__('Loading...', 'instant-guest-post-request')}</div>;
  }

  const themeOptions = [
    { value: 'light', label: __('Light Theme', 'instant-guest-post-request') },
    { value: 'dark', label: __('Dark Theme', 'instant-guest-post-request') }
  ];

  return (
    <div>
      <h2 className="igpr-text-lg igpr-font-medium igpr-text-gray-900 igpr-mb-4">
        {__('Form Style Settings', 'instant-guest-post-request')}
      </h2>
      
      <p className="igpr-mb-4 igpr-text-gray-600">
        {__('Configure the appearance of the guest post submission form.', 'instant-guest-post-request')}
      </p>
      
      <FormField
        label={__('Default Theme', 'instant-guest-post-request')}
        id="default_theme"
        type="select"
        value={settings.default_theme || 'light'}
        onChange={(value) => onChange('default_theme', value)}
        options={themeOptions}
        tooltip={__('Select the default theme for the submission form. Users can still toggle between light and dark themes.', 'instant-guest-post-request')}
      />
      
      <div className="igpr-mt-8">
        <h3 className="igpr-text-md igpr-font-medium igpr-text-gray-900 igpr-mb-4">
          {__('Theme Preview', 'instant-guest-post-request')}
        </h3>
        
        <div className="igpr-grid igpr-grid-cols-1 igpr-gap-6 igpr-mt-4 md:igpr-grid-cols-2">
          {/* Light Theme Preview */}
          <div className="igpr-border igpr-rounded-md igpr-overflow-hidden">
            <div className="igpr-bg-gray-100 igpr-px-4 igpr-py-2 igpr-border-b">
              <h4 className="igpr-text-sm igpr-font-medium">{__('Light Theme', 'instant-guest-post-request')}</h4>
            </div>
            <div className="igpr-p-4 igpr-bg-white">
              <div className="igpr-mb-3">
                <label className="igpr-block igpr-text-sm igpr-font-medium igpr-text-gray-700 igpr-mb-1">
                  {__('Post Title', 'instant-guest-post-request')}
                </label>
                <input
                  type="text"
                  className="igpr-block igpr-w-full igpr-rounded-md igpr-border-gray-300 igpr-shadow-sm"
                  disabled
                  placeholder={__('Sample title', 'instant-guest-post-request')}
                />
              </div>
              <div className="igpr-mb-3">
                <label className="igpr-block igpr-text-sm igpr-font-medium igpr-text-gray-700 igpr-mb-1">
                  {__('Author Name', 'instant-guest-post-request')}
                </label>
                <input
                  type="text"
                  className="igpr-block igpr-w-full igpr-rounded-md igpr-border-gray-300 igpr-shadow-sm"
                  disabled
                  placeholder={__('John Doe', 'instant-guest-post-request')}
                />
              </div>
              <button
                type="button"
                className="igpr-mt-2 igpr-inline-flex igpr-items-center igpr-px-4 igpr-py-2 igpr-border igpr-border-transparent igpr-text-sm igpr-font-medium igpr-rounded-md igpr-shadow-sm igpr-text-white igpr-bg-wp-primary"
                disabled
              >
                {__('Submit', 'instant-guest-post-request')}
              </button>
            </div>
          </div>
          
          {/* Dark Theme Preview */}
          <div className="igpr-border igpr-rounded-md igpr-overflow-hidden">
            <div className="igpr-bg-gray-800 igpr-px-4 igpr-py-2 igpr-border-b igpr-border-gray-700">
              <h4 className="igpr-text-sm igpr-font-medium igpr-text-white">{__('Dark Theme', 'instant-guest-post-request')}</h4>
            </div>
            <div className="igpr-p-4 igpr-bg-gray-900">
              <div className="igpr-mb-3">
                <label className="igpr-block igpr-text-sm igpr-font-medium igpr-text-gray-200 igpr-mb-1">
                  {__('Post Title', 'instant-guest-post-request')}
                </label>
                <input
                  type="text"
                  className="igpr-block igpr-w-full igpr-rounded-md igpr-border-gray-600 igpr-bg-gray-800 igpr-text-white igpr-shadow-sm"
                  disabled
                  placeholder={__('Sample title', 'instant-guest-post-request')}
                />
              </div>
              <div className="igpr-mb-3">
                <label className="igpr-block igpr-text-sm igpr-font-medium igpr-text-gray-200 igpr-mb-1">
                  {__('Author Name', 'instant-guest-post-request')}
                </label>
                <input
                  type="text"
                  className="igpr-block igpr-w-full igpr-rounded-md igpr-border-gray-600 igpr-bg-gray-800 igpr-text-white igpr-shadow-sm"
                  disabled
                  placeholder={__('John Doe', 'instant-guest-post-request')}
                />
              </div>
              <button
                type="button"
                className="igpr-mt-2 igpr-inline-flex igpr-items-center igpr-px-4 igpr-py-2 igpr-border igpr-border-transparent igpr-text-sm igpr-font-medium igpr-rounded-md igpr-shadow-sm igpr-text-white igpr-bg-blue-600"
                disabled
              >
                {__('Submit', 'instant-guest-post-request')}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default FormStyleTab;
