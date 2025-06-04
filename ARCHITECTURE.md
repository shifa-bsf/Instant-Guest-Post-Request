# Instant Guest Post Request - Architecture

This document outlines the architecture and structure of the Instant Guest Post Request WordPress plugin.

## Overview

The plugin follows a modular architecture with clear separation of concerns between frontend, backend, and admin components. It uses modern JavaScript (React) for the admin interface and WordPress REST API for data handling.

## Directory Structure

```
instant-guest-post-request/
├── build/                      # Compiled assets (JS, CSS)
├── includes/                   # PHP classes for backend functionality
│   ├── class-igpr-admin.php    # Admin interface handling
│   ├── class-igpr-form-handler.php # Frontend form processing
│   ├── class-igpr-notification.php # Email notification system
│   ├── class-igpr-post-handler.php # Post creation and management
│   ├── class-igpr-rest-api.php # REST API endpoints
│   └── class-igpr-settings.php # Settings management
├── languages/                  # Translation files
├── node_modules/               # NPM dependencies (not in repo)
├── src/                        # Source files for JS/CSS
│   ├── components/             # React components
│   │   ├── common/             # Reusable UI components
│   │   │   ├── Badge.js        # Status indicator component
│   │   │   ├── Button.js       # Button component
│   │   │   ├── Card.js         # Card container component
│   │   │   ├── FormField.js    # Form field component
│   │   │   ├── Notification.js # Notification component
│   │   │   └── Tabs.js         # Tabs navigation component
│   │   ├── tabs/               # Tab content components
│   │   │   ├── FormStyleTab.js # Form styling settings
│   │   │   ├── GeneralTab.js   # General settings
│   │   │   └── NotificationsTab.js # Email notification settings
│   │   ├── App.js              # Main React application
│   │   └── TabNavigation.js    # Tab navigation component
│   ├── styles/                 # CSS styles
│   │   └── admin.css           # Admin interface styles with Tailwind
│   └── admin.js                # Admin entry point
├── templates/                  # Frontend templates
│   └── form-template.php       # Guest post submission form template
├── .gitignore                  # Git ignore file
├── ARCHITECTURE.md             # This file
├── instant-guest-post-request.php # Main plugin file
├── LICENSE                     # License file
├── package.json                # NPM package configuration
├── postcss.config.js           # PostCSS configuration
├── PRD.md                      # Product Requirements Document
├── README.md                   # Plugin documentation
└── tailwind.config.js          # Tailwind CSS configuration
```

## Core Components

### 1. Main Plugin Class (`instant-guest-post-request.php`)

The main plugin file initializes the plugin and loads all required components. It handles:
- Plugin activation/deactivation hooks
- Loading text domain for translations
- Initializing all plugin components

### 2. Form Handler (`class-igpr-form-handler.php`)

Responsible for:
- Registering the shortcode for the submission form
- Rendering the form template
- Processing form submissions
- Validating user input
- Handling file uploads (if applicable)

### 3. Post Handler (`class-igpr-post-handler.php`)

Manages:
- Creating draft posts from form submissions
- Setting post meta data
- Handling post status changes (approve/reject)
- Managing post categories and taxonomies

### 4. Notification System (`class-igpr-notification.php`)

Handles:
- Sending email notifications to admins
- Sending auto-reply emails to submitters
- Sending approval/rejection notifications
- Processing email templates with placeholders

### 5. Settings Management (`class-igpr-settings.php`)

Manages:
- Storing plugin settings in the WordPress database
- Providing default settings
- Validating setting values

### 6. Admin Interface (`class-igpr-admin.php`)

Handles:
- Creating the admin menu
- Enqueuing admin scripts and styles
- Rendering the React-based settings page

### 7. REST API (`class-igpr-rest-api.php`)

Provides:
- Custom REST API endpoints for the React admin interface
- Endpoints for retrieving and updating settings
- Endpoints for post management (approve/reject)

## Frontend Architecture

The frontend submission form is rendered using a PHP template with the shortcode `[guest_post_form]`. The form is styled using Tailwind CSS classes with the `igpr-` prefix to avoid conflicts with theme styles.

## Admin Interface Architecture

The admin interface is built using:
- React for component-based UI
- WordPress REST API for data fetching and updates
- Tailwind CSS for styling (with prefix to avoid conflicts)

The React application is organized into:
- Common components (buttons, form fields, etc.)
- Tab-specific components for different settings sections
- Main App component that orchestrates the UI

## Data Flow

1. **Form Submission**:
   - User submits the form on the frontend
   - Form Handler validates the submission
   - Post Handler creates a draft post
   - Notification System sends emails as configured

2. **Admin Management**:
   - Admin reviews submissions in the WordPress dashboard
   - Admin can approve/reject submissions
   - Notification System sends appropriate emails based on the decision

3. **Settings Management**:
   - Admin configures settings via the React interface
   - Settings are saved via REST API
   - Settings are retrieved from the database when needed

## Build Process

The build process uses `@wordpress/scripts` to:
- Compile React components
- Process CSS with Tailwind
- Generate production-ready assets

## Security Considerations

- All user input is sanitized and validated
- REST API endpoints are secured with nonces and capability checks
- Rate limiting is implemented to prevent spam submissions
- Email templates are sanitized to prevent injection attacks
