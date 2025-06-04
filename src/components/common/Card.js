/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Card component for admin settings
 */
const Card = ({ title, children, footer, className = '' }) => {
  return (
    <div className={`igpr-card ${className}`}>
      {title && (
        <div className="igpr-border-b igpr-border-gray-200 igpr-pb-4 igpr-mb-4">
          <h2 className="igpr-text-lg igpr-font-medium igpr-text-gray-900">{title}</h2>
        </div>
      )}
      
      <div className="igpr-space-y-4">
        {children}
      </div>
      
      {footer && (
        <div className="igpr-border-t igpr-border-gray-200 igpr-mt-6 igpr-pt-4">
          {footer}
        </div>
      )}
    </div>
  );
};

export default Card;
