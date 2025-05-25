# Tency Multi-Tenancy Setup

This document explains how Tency is configured for multi-tenancy where each store has its own separate database and can be accessed via different domains.

## Architecture Overview

Tency uses a **database-per-tenant** approach with domain-based tenant resolution:

- **Landlord Database**: Stores tenant information (stores table)
- **Tenant Databases**: Each store has its own database containing categories and products
- **Domain Resolution**: Each store is accessible via its own domain

## Key Components

### 1. Store Model (`app/Models/Store.php`)
- Implements `IsTenant` interface from Spatie Multitenancy
- Auto-generates domain and database name on creation
- Automatically creates tenant database and runs migrations

### 2. Tenant Database Service (`app/Services/TenantDatabaseService.php`)
- Handles database creation and management
- Runs tenant-specific migrations
- Manages database connections

### 3. Custom Tenant Finder (`app/TenantFinder/DomainTenantFinder.php`)
- Resolves tenants based on domain
- Used by Spatie Multitenancy middleware

### 4. Models with Tenant Connection
- `Category` and `Product` models use `UsesTenantConnection` trait
- Automatically use the correct tenant database

## Database Structure

### Landlord Database (Main)
- `stores` - Store information and tenant configuration
- `users` - System users (if needed)
- Other system-wide tables

### Tenant Databases (Per Store)
- `categories` - Store-specific categories
- `products` - Store-specific products
- Other store-specific data

## Domain Configuration

### Development Setup
Stores are automatically assigned domains like:
- `store-name.localhost`
- `another-store.localhost`

### Production Setup
Configure your DNS to point store domains to your application:
- `store1.yourdomain.com`
- `store2.yourdomain.com`

## Usage

### Creating a New Store
1. Register via `/register`
2. System automatically:
   - Generates domain (`store-name.localhost`)
   - Creates database (`tenant_store_name`)
   - Runs tenant migrations
   - Sets up the store

### Accessing Stores
- **Landlord**: `http://localhost` (main application)
- **Tenant**: `http://store-name.localhost` (individual store)

### Managing Tenants

#### Run Migrations for All Tenants
```bash
php artisan tenant:migrate
```

#### Run Migrations for Specific Tenant
```bash
php artisan tenant:migrate 1
```

#### Fresh Migrations (Drop and Recreate)
```bash
php artisan tenant:migrate --fresh
```

## File Structure

```
app/
├── Console/Commands/
│   └── TenantMigrate.php          # Tenant migration command
├── Http/Controllers/
│   └── HomeController.php         # Handles both landlord and tenant routes
├── Models/
│   ├── Store.php                  # Tenant model
│   ├── Category.php               # Tenant-aware model
│   └── Product.php                # Tenant-aware model
├── Services/
│   └── TenantDatabaseService.php  # Database management
└── TenantFinder/
    └── DomainTenantFinder.php     # Custom tenant resolution

database/migrations/
├── tenant/                        # Tenant-specific migrations
│   ├── create_categories_table.php
│   └── create_products_table.php
└── [regular migrations]           # Landlord migrations

resources/views/
├── tenant/                        # Tenant-specific views
│   ├── home.blade.php
│   ├── category.blade.php
│   └── product.blade.php
└── layouts/
    └── tenant.blade.php           # Tenant layout
```

## Configuration Files

### `config/multitenancy.php`
- Tenant finder configuration
- Database connection settings
- Tenant model specification

### `config/database.php`
- Tenant database connection
- Landlord database connection

## Development Tips

### Local Development with Domains
Add to your `hosts` file:
```
127.0.0.1 store-name.localhost
127.0.0.1 another-store.localhost
```

### Testing Different Stores
1. Create stores via registration
2. Note the generated domain
3. Access via the domain to see tenant-specific content

## Security Considerations

1. **Tenant Isolation**: Each store's data is completely isolated in separate databases
2. **Domain Validation**: Only active stores with valid domains can be accessed
3. **Authorization**: Store owners can only access their own data
4. **Database Security**: Each tenant database is separate, preventing data leakage

## Troubleshooting

### Store Not Found
- Check if domain is correctly configured
- Verify store is active (`is_active = true`)
- Ensure tenant database exists

### Migration Issues
- Run `php artisan tenant:migrate` to ensure all tenant databases are up to date
- Check database permissions
- Verify tenant database connection configuration

### Domain Resolution
- Ensure DNS/hosts file is configured correctly
- Check that the domain matches exactly (case-sensitive)
- Verify web server configuration for multiple domains
