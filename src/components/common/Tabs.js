/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Enhanced Tabs component with Tailwind styling
 */
const Tabs = ({ tabs, activeTab, onTabChange, variant = 'underline' }) => {
  const getTabStyles = (tabId) => {
    const isActive = activeTab === tabId;
    
    if (variant === 'pills') {
      return isActive
        ? 'igpr-bg-wp-primary igpr-text-white'
        : 'igpr-text-gray-600 hover:igpr-text-gray-800 hover:igpr-bg-gray-100';
    }
    
    if (variant === 'boxed') {
      return isActive
        ? 'igpr-bg-white igpr-border-gray-200 igpr-border-b-white igpr-rounded-t-lg'
        : 'igpr-bg-gray-50 igpr-text-gray-500 hover:igpr-text-gray-700 hover:igpr-bg-gray-100';
    }
    
    // Default: underline
    return isActive
      ? 'igpr-border-wp-primary igpr-text-wp-primary'
      : 'igpr-border-transparent igpr-text-gray-500 hover:igpr-text-wp-primary hover:igpr-border-wp-primary';
  };

  const getContainerStyles = () => {
    if (variant === 'pills') {
      return 'igpr-flex igpr-space-x-1 igpr-p-1 igpr-bg-gray-100 igpr-rounded-lg';
    }
    
    if (variant === 'boxed') {
      return 'igpr-flex igpr-border-b igpr-border-gray-200';
    }
    
    // Default: underline
    return 'igpr-border-b igpr-border-gray-200';
  };

  return (
    <div className={getContainerStyles()}>
      <nav className="igpr-flex igpr-space-x-4" aria-label="Tabs">
        {tabs.map((tab) => (
          <button
            key={tab.id}
            onClick={() => onTabChange(tab.id)}
            className={`igpr-px-4 igpr-py-2 igpr-font-medium igpr-rounded-md igpr-transition-colors ${
              variant === 'underline' ? 'igpr-rounded-t-lg igpr-border-b-2' : ''
            } ${getTabStyles(tab.id)}`}
            aria-current={activeTab === tab.id ? 'page' : undefined}
          >
            {tab.icon && <span className="igpr-mr-2">{tab.icon}</span>}
            {tab.label}
            {tab.count !== undefined && (
              <span className="igpr-ml-2 igpr-bg-gray-200 igpr-text-gray-700 igpr-py-0.5 igpr-px-2 igpr-rounded-full igpr-text-xs">
                {tab.count}
              </span>
            )}
          </button>
        ))}
      </nav>
    </div>
  );
};

export default Tabs;
