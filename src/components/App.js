/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import TabNavigation from './TabNavigation';
import GeneralTab from './tabs/GeneralTab';
import NotificationsTab from './tabs/NotificationsTab';
import FormStyleTab from './tabs/FormStyleTab';
import Notification from './common/Notification';
import Button from './common/Button';
import Card from './common/Card';
import ForceUIExample from './ForceUIExample';

const App = () => {
  const [activeTab, setActiveTab] = useState('general');
  const [settings, setSettings] = useState({});
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [notification, setNotification] = useState(null);
  const [categories, setCategories] = useState([]);

  // Fetch settings and categories on component mount
  useEffect(() => {
    const fetchData = async () => {
      try {
        // Fetch settings
        const settingsResponse = await apiFetch({ 
          path: '/igpr/v1/settings' 
        });
        
        // Fetch categories
        const categoriesResponse = await apiFetch({ 
          path: '/wp/v2/categories?per_page=100' 
        });
        
        setSettings(settingsResponse);
        setCategories(categoriesResponse);
        setLoading(false);
      } catch (error) {
        console.error('Error fetching data:', error);
        setNotification({
          type: 'error',
          message: __('Failed to load settings. Please refresh the page.', 'instant-guest-post-request')
        });
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  // Handle settings change
  const handleSettingChange = (key, value) => {
    setSettings({
      ...settings,
      [key]: value
    });
  };

  // Save settings
  const saveSettings = async () => {
    setSaving(true);
    try {
      const response = await apiFetch({
        path: '/igpr/v1/settings',
        method: 'POST',
        data: settings
      });
      
      setNotification({
        type: 'success',
        message: __('Settings saved successfully!', 'instant-guest-post-request')
      });
      
      // Clear notification after 3 seconds
      setTimeout(() => {
        setNotification(null);
      }, 3000);
      
    } catch (error) {
      console.error('Error saving settings:', error);
      setNotification({
        type: 'error',
        message: __('Failed to save settings. Please try again.', 'instant-guest-post-request')
      });
    } finally {
      setSaving(false);
    }
  };

  // Tabs configuration
  const tabs = [
    { 
      id: 'general', 
      label: __('General', 'instant-guest-post-request'),
      component: <GeneralTab 
                  settings={settings} 
                  onChange={handleSettingChange} 
                  categories={categories} 
                  loading={loading} 
                />
    },
    { 
      id: 'notifications', 
      label: __('Notifications', 'instant-guest-post-request'),
      component: <NotificationsTab 
                  settings={settings} 
                  onChange={handleSettingChange} 
                  loading={loading} 
                />
    },
    { 
      id: 'form-style', 
      label: __('Form Style', 'instant-guest-post-request'),
      component: <FormStyleTab 
                  settings={settings} 
                  onChange={handleSettingChange} 
                  loading={loading} 
                />
    }
  ];

  return (
    <div className="igpr-p-6 igpr-max-w-7xl igpr-mx-auto">
      <div className="igpr-flex igpr-justify-between igpr-items-center igpr-mb-6">
        <h1 className="igpr-text-2xl igpr-font-bold igpr-text-gray-900">
          {__('Guest Post Plugin Settings', 'instant-guest-post-request')}
        </h1>
        <Button 
          onClick={saveSettings}
          disabled={saving || loading}
          isLoading={saving}
        >
          {saving 
            ? __('Saving...', 'instant-guest-post-request') 
            : __('Save Settings', 'instant-guest-post-request')
          }
        </Button>
      </div>

      {notification && (
        <Notification 
          type={notification.type} 
          message={notification.message} 
          onDismiss={() => setNotification(null)} 
        />
      )}

      <div className="igpr-bg-white igpr-rounded-lg igpr-shadow igpr-p-6 igpr-mb-6">
        <TabNavigation 
          tabs={tabs} 
          activeTab={activeTab} 
          onTabChange={setActiveTab} 
        />
        
        <div className="igpr-mt-6">
          {tabs.find(tab => tab.id === activeTab)?.component}
        </div>
      </div>

      <div className="igpr-bg-white igpr-rounded-lg igpr-shadow igpr-p-6 igpr-mb-6 igpr-mt-6">
        <h2 className="igpr-text-lg igpr-font-medium igpr-text-gray-900 igpr-mb-4">
          {__('Shortcode Usage', 'instant-guest-post-request')}
        </h2>
        <p className="igpr-mb-2">
          {__('Use the following shortcode to display the guest post submission form on any page or post:', 'instant-guest-post-request')}
        </p>
        <code className="igpr-bg-gray-100 igpr-p-2 igpr-rounded igpr-text-sm igpr-font-mono igpr-block">
          [guest_post_form]
        </code>
      </div>
      
      {/* Force UI Example Component */}
      <ForceUIExample />
    </div>
  );
};

export default App;
