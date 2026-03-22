# Qaid Store

Qaid Store is a Laravel 12 e-commerce application with a public storefront, cart and checkout flow, and a Filament-based admin panel for managing products, orders, coupons, admin users, SEO, and business settings.

## Stack

- PHP 8.2+
- Laravel 12
- Filament 4
- MySQL
- Vite
- Alpine.js
- Tailwind CSS
- Astrotomic Laravel Translatable
- Laravel Fortify

## Main Features

### Storefront

- Product and package browsing
- Cart management for guest users and authenticated users
- Checkout flow with city-based delivery pricing
- Delivery discussion option for configured cities
- First-order free delivery support
- Instapay and cash on delivery payment options
- Google authentication support

### Coupon System

- Coupon management from Filament admin
- Coupon creation by super admins and sales admins
- Active and inactive coupon states
- Start and expiry date support
- Global coupons or scoped coupons
- Scope support for product, category, and package targets
- Case-insensitive coupon validation
- Guest and authenticated checkout coupon support
- Discount preview on checkout before order submission
- Coupon saved on orders through `coupon_id`
- Coupon code and discount percentage shown on invoice

### Orders And Invoices

- Order creation from cart items
- Invoice page for each order
- Sales admin name shown on invoice
- Coupon details shown on invoice
- Sales admin ownership enforced for invoice access
- Sales admins can only view their own created orders and invoices
- Orders created through a coupon are assigned to the sales admin who created that coupon

### Sales Admin Reporting

- Sales Admin Invoices resource in Filament
- Invoice count by sales admin
- Total invoice amount by sales admin
- Drill-down from sales admin summary to that admin's orders
- Access restricted to super admins and accounting admins

### Dashboard Widgets

- Admins Overview widget
- Sales Stats widget
- Sales Admins Invoices widget
- Best seller and highest total sales summaries
- Quick links from widgets into filtered admin resources

### Admin Roles And Access Control

- `super_admin`
- `sales_admin`
- `accounting_admin`
- `asset_admin`
- Approval flow support for admin users
- Resource visibility restrictions by role
- Products, packages, routines, and categories hidden from accounting admins

### Admin Management

- Admin registration flow
- Admin users Filament resource
- Approval-related admin management
- Custom Filament login integration

### Business And Platform Settings

- SMTP settings management from admin panel
- SEO settings management from admin panel
- Business info resource updates
- Order settings resource updates
- Content management resource updates

### Localization And Time Handling

- English and Arabic support
- Coupon validation and activation aligned with `Africa/Cairo` timezone
- Clear coupon validation messages for not found, inactive, future start, expired, and scope mismatch states

## Recent Implemented Work

- Added `admin_creator_id` tracking on orders
- Added coupon table and coupon-to-order relation
- Added coupon scope morph relation
- Added checkout coupon input, apply, and remove flow
- Added backend coupon validation endpoint
- Added invoice coupon rendering
- Added sales-admin order ownership filtering
- Added invoice authorization guard for sales admins
- Added sales reporting resources and widgets
- Added admin overview widget
- Added SEO and SMTP management resources
- Updated application timezone to Cairo for correct coupon activation timing

## Project Structure Highlights

- `app/Filament` contains admin resources, pages, auth customizations, and widgets
- `app/Http/Controllers/Web` contains storefront controllers including checkout and coupon validation
- `app/Commands` contains order creation pipeline commands
- `app/Services` contains cart and order-related services
- `resources/views/web` contains storefront Blade views
- `resources/views/dashboard/orders` contains invoice templates
- `database/migrations` contains schema history for coupons, admin approvals, SMTP, SEO, and order ownership

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

Or use the provided setup script:

```bash
composer run setup
```

## Development

Start the full local development workflow:

```bash
composer run dev
```

This runs:

- Laravel development server
- Queue listener
- Laravel Pail log viewer
- Vite dev server

## Testing

```bash
composer run test
```

## Notes

- Coupon dates are interpreted using the application timezone configured in `config/app.php`.
- Coupon ownership can affect order attribution because orders created with a coupon are assigned to the coupon creator.
- Some admin resources are intentionally hidden based on role.

## License

This project is built on Laravel and follows the repository's licensing terms.
