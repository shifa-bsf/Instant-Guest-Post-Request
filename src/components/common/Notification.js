/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

const Notification = ({ type, message, onDismiss }) => {
  return (
    <div className={`igpr-p-4 igpr-mb-4 igpr-rounded-md ${type === 'success' ? 'igpr-bg-green-50 igpr-text-green-800' : 'igpr-bg-red-50 igpr-text-red-800'}`}>
      <div className="igpr-flex igpr-justify-between">
        <div className="igpr-flex">
          {type === 'success' ? (
            <svg className="igpr-h-5 igpr-w-5 igpr-text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
            </svg>
          ) : (
            <svg className="igpr-h-5 igpr-w-5 igpr-text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
            </svg>
          )}
          <div className="igpr-ml-3">
            <p className="igpr-text-sm">{message}</p>
          </div>
        </div>
        <button
          type="button"
          className="igpr-inline-flex igpr-bg-transparent igpr-rounded-md igpr-text-gray-400 hover:igpr-text-gray-500 focus:igpr-outline-none focus:igpr-ring-2 focus:igpr-ring-offset-2 focus:igpr-ring-wp-primary"
          onClick={onDismiss}
        >
          <span className="igpr-sr-only">{__('Close', 'instant-guest-post-request')}</span>
          <svg className="igpr-h-5 igpr-w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fillRule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clipRule="evenodd" />
          </svg>
        </button>
      </div>
    </div>
  );
};

export default Notification;
