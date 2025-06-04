/**
 * WordPress dependencies
 */
import { render } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './styles/admin.css';
import App from './components/App';

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('igpr-react-admin-app');
  if (container) {
    render(<App />, container);
  }
});
