/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Button component with Tailwind styling
 */
const Button = ({ 
  children, 
  onClick, 
  type = 'button', 
  variant = 'primary', 
  size = 'md',
  disabled = false,
  className = '',
  icon = null,
  iconPosition = 'left',
  isLoading = false
}) => {
  const getVariantClasses = () => {
    switch (variant) {
      case 'primary':
        return 'igpr-inline-flex igpr-justify-center igpr-py-2 igpr-px-4 igpr-border igpr-border-transparent igpr-shadow-sm igpr-text-sm igpr-font-medium igpr-rounded-md igpr-text-white igpr-bg-wp-primary hover:igpr-bg-wp-primary-hover focus:igpr-outline-none focus:igpr-ring-2 focus:igpr-ring-offset-2 focus:igpr-ring-wp-primary';
      case 'secondary':
        return 'igpr-inline-flex igpr-justify-center igpr-py-2 igpr-px-4 igpr-border igpr-border-wp-border igpr-shadow-sm igpr-text-sm igpr-font-medium igpr-rounded-md igpr-text-wp-text igpr-bg-wp-secondary hover:igpr-bg-wp-secondary-hover focus:igpr-outline-none focus:igpr-ring-2 focus:igpr-ring-offset-2 focus:igpr-ring-wp-primary';
      case 'danger':
        return 'igpr-inline-flex igpr-justify-center igpr-py-2 igpr-px-4 igpr-border igpr-border-transparent igpr-shadow-sm igpr-text-sm igpr-font-medium igpr-rounded-md igpr-text-white igpr-bg-red-600 hover:igpr-bg-red-700 focus:igpr-outline-none focus:igpr-ring-2 focus:igpr-ring-offset-2 focus:igpr-ring-red-500';
      case 'outline':
        return 'igpr-inline-flex igpr-justify-center igpr-py-2 igpr-px-4 igpr-border igpr-border-gray-300 igpr-shadow-sm igpr-text-sm igpr-font-medium igpr-rounded-md igpr-text-gray-700 igpr-bg-white hover:igpr-bg-gray-50 focus:igpr-outline-none focus:igpr-ring-2 focus:igpr-ring-offset-2 focus:igpr-ring-wp-primary';
      case 'link':
        return 'igpr-inline-flex igpr-justify-center igpr-py-2 igpr-px-4 igpr-text-sm igpr-font-medium igpr-text-wp-primary hover:igpr-text-wp-primary-hover focus:igpr-outline-none focus:igpr-underline';
      default:
        return 'igpr-inline-flex igpr-justify-center igpr-py-2 igpr-px-4 igpr-border igpr-border-transparent igpr-shadow-sm igpr-text-sm igpr-font-medium igpr-rounded-md igpr-text-white igpr-bg-wp-primary hover:igpr-bg-wp-primary-hover focus:igpr-outline-none focus:igpr-ring-2 focus:igpr-ring-offset-2 focus:igpr-ring-wp-primary';
    }
  };

  const getSizeClasses = () => {
    switch (size) {
      case 'xs':
        return 'igpr-py-1 igpr-px-2 igpr-text-xs';
      case 'sm':
        return 'igpr-py-1.5 igpr-px-3 igpr-text-sm';
      case 'lg':
        return 'igpr-py-2.5 igpr-px-5 igpr-text-base';
      case 'xl':
        return 'igpr-py-3 igpr-px-6 igpr-text-lg';
      default:
        return ''; // Default size is already in the variant classes
    }
  };

  return (
    <button
      type={type}
      onClick={onClick}
      disabled={disabled || isLoading}
      className={`${getVariantClasses()} ${getSizeClasses()} ${className} ${disabled ? 'igpr-opacity-60 igpr-cursor-not-allowed' : ''}`}
    >
      {isLoading && (
        <svg className="igpr-animate-spin igpr-h-4 igpr-w-4 igpr-mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle className="igpr-opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
          <path className="igpr-opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      )}
      
      {icon && iconPosition === 'left' && !isLoading && (
        <span className="igpr-mr-2">{icon}</span>
      )}
      
      {children}
      
      {icon && iconPosition === 'right' && (
        <span className="igpr-ml-2">{icon}</span>
      )}
    </button>
  );
};

export default Button;
