/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.{js,jsx,ts,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        'wp-admin': '#f0f0f1',
        'wp-primary': '#2271b1',
        'wp-primary-hover': '#135e96',
        'wp-secondary': '#f6f7f7',
        'wp-secondary-hover': '#f0f0f1',
        'wp-text': '#3c434a',
        'wp-border': '#c3c4c7',
      },
    },
  },
  plugins: [],
  // Prefix all Tailwind classes to avoid conflicts with WordPress admin styles
  prefix: 'igpr-',
  // Important to ensure Tailwind styles override WordPress admin styles
  important: true,
  // Disable core plugins that might conflict with WordPress admin styles
  corePlugins: {
    preflight: false,
  },
}
