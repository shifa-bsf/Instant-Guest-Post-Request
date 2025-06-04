/**
 * External dependencies
 */
import React from 'react';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Force UI components
 */
import { 
  Button, 
  Card, 
  CardBody, 
  CardFooter, 
  CardHeader, 
  FormToggle, 
  Input, 
  Select, 
  Tabs, 
  TabList, 
  Tab, 
  TabPanel, 
  TabPanels 
} from '@bsf/force-ui';

/**
 * Force UI Example Component
 */
const ForceUIExample = () => {
  const [activeTab, setActiveTab] = React.useState(0);
  const [toggleValue, setToggleValue] = React.useState(false);
  const [inputValue, setInputValue] = React.useState('');
  const [selectValue, setSelectValue] = React.useState('');

  const selectOptions = [
    { value: 'option1', label: 'Option 1' },
    { value: 'option2', label: 'Option 2' },
    { value: 'option3', label: 'Option 3' },
  ];

  return (
    <div className="igpr-mt-8">
      <h2 className="igpr-text-xl igpr-font-bold igpr-mb-4">
        {__('Force UI Components Example', 'instant-guest-post-request')}
      </h2>

      <Card className="igpr-mb-6">
        <CardHeader>
          <h3 className="igpr-text-lg igpr-font-medium">
            {__('Force UI Card Component', 'instant-guest-post-request')}
          </h3>
        </CardHeader>
        <CardBody>
          <p className="igpr-mb-4">
            {__('This is an example of Force UI components integration.', 'instant-guest-post-request')}
          </p>

          <div className="igpr-mb-4">
            <label className="igpr-block igpr-mb-2 igpr-font-medium">
              {__('Toggle Example', 'instant-guest-post-request')}
            </label>
            <FormToggle
              checked={toggleValue}
              onChange={() => setToggleValue(!toggleValue)}
              label={toggleValue ? __('On', 'instant-guest-post-request') : __('Off', 'instant-guest-post-request')}
            />
          </div>

          <div className="igpr-mb-4">
            <label className="igpr-block igpr-mb-2 igpr-font-medium">
              {__('Input Example', 'instant-guest-post-request')}
            </label>
            <Input
              value={inputValue}
              onChange={(e) => setInputValue(e.target.value)}
              placeholder={__('Type something...', 'instant-guest-post-request')}
            />
          </div>

          <div className="igpr-mb-4">
            <label className="igpr-block igpr-mb-2 igpr-font-medium">
              {__('Select Example', 'instant-guest-post-request')}
            </label>
            <Select
              value={selectValue}
              onChange={(e) => setSelectValue(e.target.value)}
              options={selectOptions}
              placeholder={__('Select an option', 'instant-guest-post-request')}
            />
          </div>
        </CardBody>
        <CardFooter>
          <Button variant="primary" onClick={() => alert('Button clicked!')}>
            {__('Primary Button', 'instant-guest-post-request')}
          </Button>
          <Button variant="secondary" className="igpr-ml-2">
            {__('Secondary Button', 'instant-guest-post-request')}
          </Button>
        </CardFooter>
      </Card>

      <Card>
        <CardHeader>
          <h3 className="igpr-text-lg igpr-font-medium">
            {__('Force UI Tabs Example', 'instant-guest-post-request')}
          </h3>
        </CardHeader>
        <CardBody>
          <Tabs index={activeTab} onChange={(index) => setActiveTab(index)}>
            <TabList>
              <Tab>{__('Tab 1', 'instant-guest-post-request')}</Tab>
              <Tab>{__('Tab 2', 'instant-guest-post-request')}</Tab>
              <Tab>{__('Tab 3', 'instant-guest-post-request')}</Tab>
            </TabList>
            <TabPanels>
              <TabPanel>
                <div className="igpr-p-4">
                  <h4 className="igpr-font-medium igpr-mb-2">
                    {__('Tab 1 Content', 'instant-guest-post-request')}
                  </h4>
                  <p>
                    {__('This is the content for Tab 1.', 'instant-guest-post-request')}
                  </p>
                </div>
              </TabPanel>
              <TabPanel>
                <div className="igpr-p-4">
                  <h4 className="igpr-font-medium igpr-mb-2">
                    {__('Tab 2 Content', 'instant-guest-post-request')}
                  </h4>
                  <p>
                    {__('This is the content for Tab 2.', 'instant-guest-post-request')}
                  </p>
                </div>
              </TabPanel>
              <TabPanel>
                <div className="igpr-p-4">
                  <h4 className="igpr-font-medium igpr-mb-2">
                    {__('Tab 3 Content', 'instant-guest-post-request')}
                  </h4>
                  <p>
                    {__('This is the content for Tab 3.', 'instant-guest-post-request')}
                  </p>
                </div>
              </TabPanel>
            </TabPanels>
          </Tabs>
        </CardBody>
      </Card>
    </div>
  );
};

export default ForceUIExample;
