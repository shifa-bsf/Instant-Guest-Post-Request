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
  Title
} from '@bsf/force-ui';

/**
 * Force UI Example Component
 */
const ForceUIExample = () => {
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

      <Container className="igpr-bg-white igpr-rounded-lg igpr-shadow igpr-p-6 igpr-mb-6">
        <h3 className="igpr-text-lg igpr-font-medium igpr-mb-4">
          {__('Force UI Components', 'instant-guest-post-request')}
        </h3>
        
        <div className="igpr-mb-6">
          <p className="igpr-mb-4">
            {__('This is an example of Force UI components integration.', 'instant-guest-post-request')}
          </p>

          <div className="igpr-mb-4">
            <label className="igpr-block igpr-mb-2 igpr-font-medium">
              {__('Toggle Example', 'instant-guest-post-request')}
            </label>
            <Switch
              checked={toggleValue}
              onChange={() => setToggleValue(!toggleValue)}
            />
            <span className="igpr-ml-2">
              {toggleValue ? __('On', 'instant-guest-post-request') : __('Off', 'instant-guest-post-request')}
            </span>
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
        <h3 className="igpr-text-lg igpr-font-medium igpr-mb-4">
          {__('Badges Example', 'instant-guest-post-request')}
        </h3>
        
        <div className="igpr-flex igpr-gap-2 igpr-flex-wrap">
          <Badge variant="primary">Primary</Badge>
          <Badge variant="secondary">Secondary</Badge>
          <Badge variant="success">Success</Badge>
          <Badge variant="danger">Danger</Badge>
          <Badge variant="warning">Warning</Badge>
          <Badge variant="info">Info</Badge>
        </div>
      </Container>
    </div>
  );
};

export default ForceUIExample;
