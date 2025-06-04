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
  Container,
  Input, 
  Select, 
  Switch,
  Badge,
  Text,
  Title,
  Tabs
} from '@bsf/force-ui';

/**
 * Force UI Example Component
 */
const ForceUIExample = () => {
  const [toggleValue, setToggleValue] = React.useState(false);
  const [inputValue, setInputValue] = React.useState('');
  const [selectValue, setSelectValue] = React.useState('');
  const [activeTab, setActiveTab] = React.useState(0);

  const selectOptions = [
    { value: 'option1', label: 'Option 1' },
    { value: 'option2', label: 'Option 2' },
    { value: 'option3', label: 'Option 3' },
  ];

  const tabItems = [
    {
      title: __('Tab 1', 'instant-guest-post-request'),
      content: (
        <div className="igpr-p-4">
          <Text className="igpr-font-medium igpr-mb-2">
            {__('Tab 1 Content', 'instant-guest-post-request')}
          </Text>
          <Text>
            {__('This is the content for Tab 1.', 'instant-guest-post-request')}
          </Text>
        </div>
      )
    },
    {
      title: __('Tab 2', 'instant-guest-post-request'),
      content: (
        <div className="igpr-p-4">
          <Text className="igpr-font-medium igpr-mb-2">
            {__('Tab 2 Content', 'instant-guest-post-request')}
          </Text>
          <Text>
            {__('This is the content for Tab 2.', 'instant-guest-post-request')}
          </Text>
        </div>
      )
    },
    {
      title: __('Tab 3', 'instant-guest-post-request'),
      content: (
        <div className="igpr-p-4">
          <Text className="igpr-font-medium igpr-mb-2">
            {__('Tab 3 Content', 'instant-guest-post-request')}
          </Text>
          <Text>
            {__('This is the content for Tab 3.', 'instant-guest-post-request')}
          </Text>
        </div>
      )
    }
  ];

  return (
    <div className="igpr-mt-8">
      <Title level={2} className="igpr-mb-4">
        {__('Force UI Components Example', 'instant-guest-post-request')}
      </Title>

      <Container className="igpr-bg-white igpr-rounded-lg igpr-shadow igpr-p-6 igpr-mb-6">
        <Title level={3} className="igpr-mb-4">
          {__('Force UI Components', 'instant-guest-post-request')}
        </Title>
        
        <div className="igpr-mb-6">
          <Text className="igpr-mb-4">
            {__('This is an example of Force UI components integration.', 'instant-guest-post-request')}
          </Text>

          <div className="igpr-mb-4">
            <Text className="igpr-block igpr-mb-2 igpr-font-medium">
              {__('Toggle Example', 'instant-guest-post-request')}
            </Text>
            <div className="igpr-flex igpr-items-center">
              <Switch
                checked={toggleValue}
                onChange={() => setToggleValue(!toggleValue)}
              />
              <Text className="igpr-ml-2">
                {toggleValue ? __('On', 'instant-guest-post-request') : __('Off', 'instant-guest-post-request')}
              </Text>
            </div>
          </div>

          <div className="igpr-mb-4">
            <Text className="igpr-block igpr-mb-2 igpr-font-medium">
              {__('Input Example', 'instant-guest-post-request')}
            </Text>
            <Input
              value={inputValue}
              onChange={(e) => setInputValue(e.target.value)}
              placeholder={__('Type something...', 'instant-guest-post-request')}
            />
          </div>

          <div className="igpr-mb-4">
            <Text className="igpr-block igpr-mb-2 igpr-font-medium">
              {__('Select Example', 'instant-guest-post-request')}
            </Text>
            <Select
              value={selectValue}
              onChange={(e) => setSelectValue(e.target.value)}
              options={selectOptions}
              placeholder={__('Select an option', 'instant-guest-post-request')}
            />
          </div>
        </div>
        
        <div className="igpr-flex igpr-gap-2">
          <Button variant="primary" onClick={() => alert('Button clicked!')}>
            {__('Primary Button', 'instant-guest-post-request')}
          </Button>
          <Button variant="secondary">
            {__('Secondary Button', 'instant-guest-post-request')}
          </Button>
        </div>
      </Container>

      <Container className="igpr-bg-white igpr-rounded-lg igpr-shadow igpr-p-6 igpr-mb-6">
        <Title level={3} className="igpr-mb-4">
          {__('Badges Example', 'instant-guest-post-request')}
        </Title>
        
        <div className="igpr-flex igpr-gap-2 igpr-flex-wrap">
          <Badge variant="primary">Primary</Badge>
          <Badge variant="secondary">Secondary</Badge>
          <Badge variant="success">Success</Badge>
          <Badge variant="danger">Danger</Badge>
          <Badge variant="warning">Warning</Badge>
          <Badge variant="info">Info</Badge>
        </div>
      </Container>

      <Container className="igpr-bg-white igpr-rounded-lg igpr-shadow igpr-p-6">
        <Title level={3} className="igpr-mb-4">
          {__('Tabs Example', 'instant-guest-post-request')}
        </Title>
        
        <Tabs
          items={tabItems}
          activeTab={activeTab}
          onTabChange={setActiveTab}
        />
      </Container>
    </div>
  );
};

export default ForceUIExample;
