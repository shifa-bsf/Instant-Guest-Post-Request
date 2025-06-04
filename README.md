# Instant Guest Post Request

A WordPress plugin that allows visitors to submit guest posts from the frontend without requiring login.

## Description

Instant Guest Post Request is a powerful WordPress plugin designed to streamline the guest post submission process. It allows website visitors to submit content directly from the frontend without creating an account or logging in, while giving site administrators full control over moderation, notifications, and styling.

## Features

- **Frontend Submission Form**: Clean, responsive form for guest post submissions
- **Moderation System**: Review and approve/reject submissions before publishing
- **Email Notifications**: Customizable email templates for admin notifications and user communications
- **Spam Protection**: IP-based submission limits to prevent abuse
- **Customizable Styling**: Light and dark theme options with Tailwind CSS
- **Admin Dashboard**: React-based settings interface for easy configuration
- **Shortcode Support**: Simple shortcode to place the submission form anywhere

## Installation

1. Upload the `instant-guest-post-request` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > Guest Post Plugin to configure the plugin settings
4. Add the `[guest_post_form]` shortcode to any page or post where you want the submission form to appear

## Usage

### Basic Usage

Simply add the shortcode `[guest_post_form]` to any page or post where you want the guest post submission form to appear.

### Configuration

Navigate to Settings > Guest Post Plugin in your WordPress admin to configure:

- Default category for submissions
- Moderation requirements
- Submission limits
- Email notification templates
- Form styling options

### Email Templates

The plugin includes customizable email templates for:
- Admin notifications of new submissions
- Auto-reply messages to submitters
- Approval notifications
- Rejection notifications

All templates support placeholders like `{post_title}`, `{author_name}`, etc.

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher

## Development

This plugin is built with:
- React for the admin interface
- Tailwind CSS for styling
- WordPress REST API for data handling
- @wordpress/scripts for build processes

### Development Setup

1. Clone the repository
2. Run `npm install` to install dependencies
3. Use `npm run start` for development
4. Use `npm run build` for production builds

## License

GPL-2.0-or-later

## Credits

Developed by [Your Name/Company]
