# RentWise - Property Management WordPress Theme

## Overview

RentWise is a modern WordPress theme designed for property management, built on the Sage starter theme framework. It provides a comprehensive dashboard for managing rental properties, tenants, payments, and expenses.

## Technology Stack

### Core Framework
- **WordPress Theme**: Based on Sage 11.x by Roots
- **PHP Version**: 8.2+
- **WordPress Version**: 6.6+
- **Framework**: Laravel Acorn (Laravel components for WordPress)
- **Templating**: Laravel Blade
- **Frontend Build**: Vite 6.x
- **Styling**: Tailwind CSS 4.x

### Key Dependencies
- **Roots Acorn**: Laravel integration for WordPress
- **Laravel Blade**: Modern PHP templating engine
- **Vite**: Fast frontend build tool with HMR support
- **Tailwind CSS**: Utility-first CSS framework
- **Advanced Custom Fields (ACF)**: Custom field management

## Project Structure

```
rentwise/
├── app/                          # PHP application logic
│   ├── Providers/               # Service providers
│   ├── View/Composers/          # Blade view composers
│   ├── setup.php               # Theme setup and configuration
│   ├── filters.php             # WordPress filters
│   ├── blocks.php              # Block editor configuration
│   ├── helpers.php             # Helper functions
│   ├── posttypes.php           # Custom post type registration
│   ├── acf_json.php            # ACF JSON configuration
│   ├── kpi_card.php            # KPI card functionality
│   ├── activity_feed.php       # Activity tracking
│   ├── add_tenant.php          # Tenant creation
│   ├── get_tenant.php          # Tenant retrieval
│   ├── update_tenant.php       # Tenant updates
│   ├── delete_tenant.php       # Tenant deletion
│   ├── add_property.php        # Property creation
│   ├── get_property.php        # Property retrieval
│   ├── update_property.php     # Property updates
│   ├── delete_property.php     # Property deletion
│   ├── add_expense.php         # Expense creation
│   ├── get_expense.php         # Expense retrieval
│   ├── update_expense.php      # Expense updates
│   ├── delete_expense.php      # Expense deletion
│   ├── expense_helpers.php     # Expense helper functions
│   ├── add_payment.php         # Payment recording
│   └── get_payments.php        # Payment retrieval
├── resources/
│   ├── css/
│   │   ├── app.css            # Main application styles
│   │   └── editor.css         # Block editor styles
│   ├── js/
│   │   ├── app.js             # Main application JavaScript
│   │   └── editor.js          # Block editor JavaScript
│   ├── views/
│   │   ├── layouts/           # Layout templates
│   │   ├── components/        # Reusable components
│   │   ├── partials/          # Template partials
│   │   ├── sections/          # Page sections
│   │   ├── blocks/            # Custom blocks
│   │   └── svg/               # SVG icons
│   ├── acf-json/              # ACF field group exports
│   ├── fonts/                 # Custom fonts
│   └── images/                # Static images
├── public/                     # Compiled assets
├── vendor/                     # PHP dependencies
├── node_modules/               # Node.js dependencies
├── composer.json               # PHP dependencies
├── package.json                # Node.js dependencies
├── functions.php               # Theme entry point
├── style.css                   # Theme metadata
├── theme.json                  # WordPress theme configuration
├── vite.config.js             # Vite configuration
└── .gitignore                 # Git ignore rules
```

## Features

### 1. Custom Post Types

The theme registers four custom post types for managing rental operations:

#### Tenants (`tenant`)
- Stores tenant information
- Supports: title, thumbnail
- Menu icon: dashicons-groups
- Private post type (not publicly viewable)

#### Properties (`property`)
- Manages rental properties
- Supports: title, thumbnail
- Menu icon: dashicons-building
- Private post type

#### Payments (`payment`)
- Records tenant payments
- Supports: title
- Menu icon: dashicons-money-alt
- Private post type

#### Expenses (`expense`)
- Tracks property-related expenses
- Supports: title
- Menu icon: dashicons-cart
- Private post type

### 2. Dashboard Template

Custom dashboard template (`template-dashboard.blade.php`) provides:
- KPI cards for key metrics
- Property management interface
- Tenant management interface
- Payment tracking
- Expense tracking
- Activity feed
- User profile management

### 3. Blade Components

Reusable components in `resources/views/components/`:
- `kpi-card.blade.php` - Key performance indicator cards
- `tenant-card.blade.php` - Individual tenant display
- `dashboard-header.blade.php` - Dashboard navigation
- `add-tenant-modal.blade.php` - Add new tenant
- `edit-tenant-modal.blade.php` - Edit existing tenant
- `tenant-info-modal.blade.php` - View tenant details
- `add-property-modal.blade.php` - Add new property
- `edit-property-modal.blade.php` - Edit existing property
- `properties-list-modal.blade.php` - View all properties
- `add-expense-modal.blade.php` - Add new expense
- `edit-expense-modal.blade.php` - Edit existing expense
- `expenses-list-modal.blade.php` - View all expenses
- `record-payment-modal.blade.php` - Record tenant payment
- `monthly-revenue-modal.blade.php` - View revenue details
- `overdue-payments-modal.blade.php` - View overdue payments
- `active-tenants-list-modal.blade.php` - View active tenants
- `activity-feed.blade.php` - Recent activity display
- `user-profile-modal.blade.php` - User profile management
- `icon.blade.php` - SVG icon renderer
- `alert.blade.php` - Alert notifications

### 4. Advanced Custom Fields (ACF)

ACF field groups stored in `resources/acf-json/`:
- `group_690cea37b777a.json` - Tenant fields
- `group_69051c22239b0.json` - Property fields
- `group_6918a6b15a07e.json` - Expense fields
- `group_6905185e3ed44.json` - Payment fields

Fields are version controlled and synced automatically.

### 5. Activity Feed

Tracks and displays recent activities:
- Property additions/updates/deletions
- Tenant additions/updates/deletions
- Payment recordings
- Expense additions/updates/deletions

### 6. KPI Cards

Dashboard displays key performance indicators:
- Monthly revenue
- Total active tenants
- Total properties
- Overdue payments
- Monthly expenses

## Development Workflow

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 20.x or higher
- WordPress 6.6+
- Local development environment (Local by Flywheel, XAMPP, etc.)

### Installation

1. **Clone or install the theme**
   ```bash
   cd wp-content/themes/rentwise
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Configure environment**
   - Ensure WordPress is properly configured
   - Activate the theme in WordPress admin

### Development Commands

#### Start development server
```bash
npm run dev
```
- Starts Vite development server
- Enables hot module replacement (HMR)
- Watches for file changes
- Automatically compiles assets

#### Build for production
```bash
npm run build
```
- Compiles and minifies assets
- Optimizes for production
- Generates asset manifests

#### Translation commands
```bash
npm run translate        # Generate and update translation files
npm run translate:pot    # Generate .pot file
npm run translate:update # Update .po files
npm run translate:compile # Compile translations
```

### File Watching

Vite automatically watches and recompiles:
- `resources/css/**/*.css`
- `resources/js/**/*.js`
- `resources/views/**/*.blade.php`

### Working with Blade Templates

Blade templates are located in `resources/views/`:

#### Main Templates
- `layouts/app.blade.php` - Main layout wrapper
- `template-dashboard.blade.php` - Dashboard page
- `template-landing.blade.php` - Landing page
- `index.blade.php` - Blog index
- `single.blade.php` - Single post
- `page.blade.php` - Default page template

#### Creating Components

```blade
{{-- resources/views/components/my-component.blade.php --}}
<div class="my-component">
    {{ $slot }}
</div>
```

Usage:
```blade
<x-my-component>
    Content here
</x-my-component>
```

### Working with Tailwind CSS

Tailwind configuration is in `resources/css/app.css`:

```css
@import 'tailwindcss';
```

Tailwind CSS 4.x uses native CSS features and is configured via Vite.

## Custom Functionality

### Tenant Management

**Add Tenant** (`app/add_tenant.php`)
- Creates new tenant post
- Associates with property
- Sets tenant metadata

**Get Tenant** (`app/get_tenant.php`)
- Retrieves tenant information
- Fetches related property data
- Returns payment history

**Update Tenant** (`app/update_tenant.php`)
- Updates tenant details
- Modifies property association
- Updates ACF fields

**Delete Tenant** (`app/delete_tenant.php`)
- Removes tenant record
- Handles related data cleanup

### Property Management

**Add Property** (`app/add_property.php`)
- Creates new property post
- Sets property metadata
- Uploads property images

**Get Property** (`app/get_property.php`)
- Retrieves property information
- Fetches associated tenants
- Returns financial data

**Update Property** (`app/update_property.php`)
- Updates property details
- Modifies property metadata
- Updates property images

**Delete Property** (`app/delete_property.php`)
- Removes property record
- Handles tenant associations
- Cleans up related data

### Payment Management

**Add Payment** (`app/add_payment.php`)
- Records tenant payment
- Updates tenant balance
- Creates payment record
- Updates activity feed

**Get Payments** (`app/get_payments.php`)
- Retrieves payment history
- Filters by tenant/property
- Calculates totals

### Expense Management

**Add Expense** (`app/add_expense.php`)
- Records property expense
- Associates with property
- Updates financial calculations

**Get Expense** (`app/get_expense.php`)
- Retrieves expense information
- Filters by property/date
- Calculates totals

**Update Expense** (`app/update_expense.php`)
- Modifies expense details
- Updates property association

**Delete Expense** (`app/delete_expense.php`)
- Removes expense record
- Updates financial data

### Helper Functions

**helpers.php** (`app/helpers.php`)
- Common utility functions
- Data formatting
- Validation helpers

**expense_helpers.php** (`app/expense_helpers.php`)
- Expense-specific utilities
- Financial calculations
- Category management

## Theme Configuration

### WordPress Theme Support

Located in `app/setup.php`:

- **Title Tag**: WordPress manages document title
- **Post Thumbnails**: Featured image support
- **Responsive Embeds**: Responsive video/audio embeds
- **HTML5**: Modern markup support
- **Navigation Menus**: Primary navigation support
- **Customizer Selective Refresh**: Widget preview updates

### Disable Features

- **Full-Site Editing**: Disabled for classic theme approach
- **Core Block Patterns**: Disabled default patterns
- **Admin Bar**: Hidden for non-administrators

### Block Editor

Custom styles and scripts injected into block editor:
- `resources/css/editor.css` - Editor-specific styles
- `resources/js/editor.js` - Editor-specific JavaScript

### Theme JSON

Located in `theme.json`:
- Block editor color palette
- Typography settings
- Spacing scale
- Custom templates

## Custom Blocks

### Tenant Grid Block

Located in `resources/views/blocks/tenant-grid/`:
- `block.json` - Block registration
- `view.blade.php` - Block template
- `style.css` - Block styles
- `index.js` - Block editor script

Displays grid of active tenants with filtering and sorting.

## ACF Integration

### JSON Storage

ACF field groups are stored as JSON in `resources/acf-json/`:
- Enables version control
- Automatic sync between environments
- Faster than database storage

### Configuration

Located in `app/acf_json.php`:
```php
// Save ACF JSON to resources/acf-json
add_filter('acf/settings/save_json', ...);

// Load ACF JSON from resources/acf-json
add_filter('acf/settings/load_json', ...);
```

## Vite Configuration

Located in `vite.config.js`:

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/css/editor.css',
        'resources/js/app.js',
        'resources/js/editor.js',
      ],
      refresh: true,
    }),
    tailwindcss(),
  ],
});
```

### Entry Points

- `resources/css/app.css` - Main application styles
- `resources/css/editor.css` - Block editor styles
- `resources/js/app.js` - Main application JavaScript
- `resources/js/editor.js` - Block editor JavaScript

## Git Workflow

### Recent Commits

Based on git history:
- Payment statement improvements
- Stripe integration refinements
- Dashboard header with user avatar
- Profile viewing functionality
- Dashboard button alignment fixes
- Registration redirect improvements
- ACF configuration fixes

### Branches

- `main` - Production-ready code
- Feature branches created as needed

## Security Considerations

1. **User Permissions**: Admin bar hidden for non-administrators
2. **Private Post Types**: All custom post types are private
3. **AJAX Endpoints**: Validate nonces and user capabilities
4. **Input Sanitization**: Sanitize all user inputs
5. **Output Escaping**: Escape all outputs to prevent XSS

## Performance Optimization

1. **Vite**: Fast development builds with HMR
2. **Lazy Loading**: Components loaded as needed
3. **Asset Optimization**: Minification and compression in production
4. **ACF JSON**: Faster than database queries
5. **Composer Autoloader**: Optimized class loading

## Browser Support

Targets modern browsers:
- Chrome (last 2 versions)
- Firefox (last 2 versions)
- Safari (last 2 versions)
- Edge (last 2 versions)

## Debugging

### Enable WordPress Debug Mode

In `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### View Laravel Logs

Acorn logs are stored in WordPress uploads directory:
```
wp-content/uploads/acorn/logs/
```

### Vite Debug

Start dev server with verbose logging:
```bash
npm run dev -- --debug
```

## Deployment

### Production Checklist

1. Build assets: `npm run build`
2. Install production dependencies: `composer install --no-dev --optimize-autoloader`
3. Remove development files: `.git`, `node_modules`, `.env.example`
4. Set WP_DEBUG to false
5. Configure caching
6. Test all functionality
7. Backup database and files

### Server Requirements

- PHP 8.2+
- MySQL 5.7+ or MariaDB 10.3+
- Apache with mod_rewrite or Nginx
- Composer (for dependency installation)
- Node.js (for asset compilation)

## Troubleshooting

### Common Issues

**1. Composer autoloader not found**
```bash
composer install
```

**2. Assets not loading**
```bash
npm run build
```

**3. ACF fields not showing**
- Check ACF Pro is installed and activated
- Sync field groups in ACF settings

**4. Vite development server not working**
- Ensure port 5173 is not in use
- Check firewall settings
- Verify `package.json` scripts

**5. Blade templates not rendering**
- Clear Acorn cache
- Check file permissions
- Verify template file paths

## Support and Resources

### Sage Documentation
- [Official Docs](https://roots.io/sage/docs/)
- [GitHub Repository](https://github.com/roots/sage)
- [Community Forum](https://discourse.roots.io/)

### Laravel Blade
- [Blade Documentation](https://laravel.com/docs/blade)
- [Blade Components](https://laravel.com/docs/blade#components)

### Tailwind CSS
- [Tailwind Docs](https://tailwindcss.com/docs)
- [Tailwind 4.0 Guide](https://tailwindcss.com/docs/v4)

### WordPress
- [Developer Handbook](https://developer.wordpress.org/)
- [ACF Documentation](https://www.advancedcustomfields.com/resources/)

## License

MIT License - See LICENSE.md file for details.

## Contributing

This is a custom theme for RentWise property management. For contributions:

1. Create feature branch
2. Make changes
3. Test thoroughly
4. Submit pull request with detailed description

## Changelog

See git commit history for detailed changes.

## Credits

- Built on Sage by Roots
- Powered by Laravel Acorn
- Styled with Tailwind CSS
- Developed for RentWise property management platform
