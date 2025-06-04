/**
 * Badge component for status indicators
 */
const Badge = ({ text, type = 'default', size = 'md' }) => {
  const getTypeClasses = () => {
    switch (type) {
      case 'success':
        return 'igpr-bg-green-100 igpr-text-green-800';
      case 'warning':
        return 'igpr-bg-yellow-100 igpr-text-yellow-800';
      case 'error':
        return 'igpr-bg-red-100 igpr-text-red-800';
      case 'info':
        return 'igpr-bg-blue-100 igpr-text-blue-800';
      case 'primary':
        return 'igpr-bg-wp-primary igpr-bg-opacity-10 igpr-text-wp-primary';
      default:
        return 'igpr-bg-gray-100 igpr-text-gray-800';
    }
  };

  const getSizeClasses = () => {
    switch (size) {
      case 'sm':
        return 'igpr-text-xs igpr-px-2 igpr-py-0.5';
      case 'lg':
        return 'igpr-text-sm igpr-px-3 igpr-py-1';
      default:
        return 'igpr-text-xs igpr-px-2.5 igpr-py-0.5';
    }
  };

  return (
    <span className={`igpr-inline-flex igpr-items-center igpr-rounded-full igpr-font-medium ${getTypeClasses()} ${getSizeClasses()}`}>
      {text}
    </span>
  );
};

export default Badge;
