/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

const TabNavigation = ({ tabs, activeTab, onTabChange }) => {
  return (
    <div className="igpr-border-b igpr-border-gray-200">
      <nav className="igpr-flex igpr-space-x-4" aria-label="Tabs">
        {tabs.map((tab) => (
          <button
            key={tab.id}
            onClick={() => onTabChange(tab.id)}
            className={`igpr-px-4 igpr-py-2 igpr-font-medium igpr-rounded-t-lg igpr-border-b-2 igpr-transition-colors ${
              activeTab === tab.id
                ? 'igpr-border-wp-primary igpr-text-wp-primary'
                : 'igpr-border-transparent igpr-text-gray-500 hover:igpr-text-wp-primary hover:igpr-border-wp-primary'
            }`}
            aria-current={activeTab === tab.id ? 'page' : undefined}
          >
            {tab.label}
          </button>
        ))}
      </nav>
    </div>
  );
};

export default TabNavigation;
