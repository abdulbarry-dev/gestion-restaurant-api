# Restaurant Order Management API

A complete RESTful API built with Laravel for managing restaurant order workflows. This API provides comprehensive functionality for handling customers, menu items, tables, and orders with proper authentication and validation.

**Requirements:** PHP 8.2+, Laravel 12.x, MySQL/PostgreSQL

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Installation](#installation)
- [Database Schema](#database-schema)
- [API Endpoints](#api-endpoints)
- [Authentication](#authentication)
- [Request Examples](#request-examples)
- [Order Workflow](#order-workflow)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)
- [Project Structure](#project-structure)
- [Technologies](#technologies)
- [License](#license)

---

## Overview

This API manages the complete lifecycle of restaurant operations including:

- Customer management with profiles and order history
- Menu item management with categories and availability
- Table management with real-time status tracking
- Order processing with automatic calculations and status workflows
- Payment tracking and management
- Token-based authentication for secure API access

---

## Features

- **Authentication**: Token-based authentication using Laravel Sanctum
- **Order Management**: Complete order lifecycle from creation to completion
- **Menu Management**: Categorized menu items with availability tracking
- **Customer Management**: Customer profiles with contact information
- **Table Management**: Real-time table availability and assignment
- **Payment Tracking**: Monitor payment status for all orders
- **Filtering and Search**: Advanced filtering across all resources
- **Automatic Calculations**: Order totals computed automatically (10% tax)
- **API Versioning**: Versioned API endpoints for future compatibility

---

## Installation

### Step 1: Environment Setup

Copy the environment file and configure your database credentials:

```bash
cp .env.example .env
```

Edit `.env` with your database settings:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

Generate the application key:

```bash
php artisan key:generate
```

### Step 2: Database Setup

Create the database:

```sql
CREATE DATABASE restaurant_db;
```

Run migrations to create all tables:

```bash
php artisan migrate
```

Seed the database with sample data (recommended):

```bash
php artisan db:seed
```

This creates:
- Admin user (email: admin@restaurant.com, password: password123)
- 5 sample customers
- 10 restaurant tables (T1-T10)
- 16 menu items across categories

### Step 3: Start the Server

```bash
php artisan serve
```

The API will be available at: http://localhost:8000

---

## Database Schema

### Core Tables

**customers**
- Customer information including name, email, phone, and address
- Linked to orders through foreign key relationship

**tables**
- Restaurant table management with table number, capacity, and status
- Status: available, occupied, reserved
- Automatically updates based on order lifecycle

**menu_items**
- Menu items with name, description, price, category, and availability
- Categories: Appetizers, Main Courses, Pizzas, Desserts, Beverages

**orders**
- Order headers with customer, table, status, payment status, and totals
- Status workflow: pending, in-progress, completed, cancelled
- Payment status: unpaid, paid, refunded
- Automatic calculation of subtotal, tax (10%), and total

**order_items**
- Line items for each order linking to menu items
- Includes quantity, price, subtotal, and special instructions

**users**
- Admin and staff users for API authentication
- Extended with Laravel Sanctum for token management

**personal_access_tokens**
- API authentication tokens managed by Laravel Sanctum

### Relationships

```
Customer ──< Order >── Table
              │
              └──< OrderItem >── MenuItem
```

- Order belongs to Customer (many-to-one)
- Order belongs to Table (many-to-one)
- Order has many OrderItems (one-to-many)
- OrderItem belongs to MenuItem (many-to-one)

---

## API Endpoints

### Base URL

```
http://localhost:8000/api/v1
```

### Authentication Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | /auth/register | Register new user | No |
| POST | /auth/login | Login and get token | No |
| POST | /auth/logout | Logout and revoke token | Yes |
| GET | /auth/me | Get authenticated user | Yes |

### Menu Items

**Public Endpoints (No Authentication)**

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /menu-items | List all menu items |
| GET | /menu-items/{id} | Get single menu item |

Query Parameters:
- category: Filter by category
- is_available: Filter by availability (true/false)
- search: Search by name

**Protected Endpoints (Authentication Required)**

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /menu-items | Create new menu item |
| PUT | /menu-items/{id} | Update menu item |
| DELETE | /menu-items/{id} | Delete menu item |

### Orders (Authentication Required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /orders | List all orders |
| POST | /orders | Create new order |
| GET | /orders/{id} | Get single order |
| PUT | /orders/{id} | Update order |
| DELETE | /orders/{id} | Delete order |

Query Parameters:
- status: Filter by status (pending, in-progress, completed, cancelled)
- payment_status: Filter by payment status (unpaid, paid, refunded)
- table_id: Filter by table ID

### Customers (Authentication Required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /customers | List all customers |
| POST | /customers | Create new customer |
| GET | /customers/{id} | Get single customer with order history |
| PUT | /customers/{id} | Update customer |
| DELETE | /customers/{id} | Delete customer |

Query Parameters:
- search: Search by name, email, or phone

### Tables (Authentication Required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /tables | List all tables |
| POST | /tables | Create new table |
| GET | /tables/{id} | Get single table with orders |
| PUT | /tables/{id} | Update table |
| DELETE | /tables/{id} | Delete table |

Query Parameters:
- status: Filter by status (available, occupied, reserved)

---

## Authentication

The API uses Laravel Sanctum for token-based authentication.

### Getting a Token

Login to receive an access token:

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@restaurant.com","password":"password123"}'
```

Response:

```json
{
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@restaurant.com"
  },
  "access_token": "1|xxxxxxxxxxxxxx",
  "token_type": "Bearer"
}
```

### Using the Token

Include the token in the Authorization header for all protected endpoints:

```
Authorization: Bearer {your_access_token}
```

Example:

```bash
curl -H "Authorization: Bearer 1|xxxxxxxxxxxxxx" \
  http://localhost:8000/api/v1/orders
```

---

## Request Examples

### 1. Register New User

```http
POST /api/v1/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### 2. Create Menu Item

```http
POST /api/v1/menu-items
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Spaghetti Carbonara",
  "description": "Classic Italian pasta with bacon and cream sauce",
  "price": 16.99,
  "category": "Main Courses",
  "is_available": true
}
```

### 3. Create Order

```http
POST /api/v1/orders
Authorization: Bearer {token}
Content-Type: application/json

{
  "customer_id": 1,
  "table_id": 2,
  "notes": "Extra napkins",
  "items": [
    {
      "menu_item_id": 4,
      "quantity": 2,
      "special_instructions": "Medium rare"
    },
    {
      "menu_item_id": 8,
      "quantity": 1
    }
  ]
}
```

Response:

```json
{
  "id": 1,
  "customer_id": 1,
  "table_id": 2,
  "status": "pending",
  "payment_status": "unpaid",
  "subtotal": "45.98",
  "tax": "4.60",
  "total": "50.58",
  "notes": "Extra napkins",
  "customer": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "table": {
    "id": 2,
    "table_number": "T2",
    "status": "occupied"
  },
  "order_items": [
    {
      "id": 1,
      "menu_item_id": 4,
      "quantity": 2,
      "price": "22.99",
      "subtotal": "45.98",
      "special_instructions": "Medium rare",
      "menu_item": {
        "id": 4,
        "name": "Grilled Salmon",
        "category": "Main Courses"
      }
    }
  ]
}
```

### 4. Update Order Status

```http
PUT /api/v1/orders/1
Authorization: Bearer {token}
Content-Type: application/json

{
  "status": "completed",
  "payment_status": "paid"
}
```

### 5. Create Customer

```http
POST /api/v1/customers
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Sarah Miller",
  "email": "sarah@example.com",
  "phone": "+1-555-0106",
  "address": "987 Cedar Ln, Miami, FL 33101"
}
```

### 6. Filter Orders

```bash
# Get all pending orders
curl -H "Authorization: Bearer {token}" \
  "http://localhost:8000/api/v1/orders?status=pending"

# Get unpaid orders for a specific table
curl -H "Authorization: Bearer {token}" \
  "http://localhost:8000/api/v1/orders?table_id=2&payment_status=unpaid"
```

### 7. Search Customers

```bash
curl -H "Authorization: Bearer {token}" \
  "http://localhost:8000/api/v1/customers?search=sarah"
```

### 8. Browse Menu by Category

```bash
# Public endpoint - no authentication required
curl "http://localhost:8000/api/v1/menu-items?category=Pizzas&is_available=true"
```

---

## Order Workflow

### Order Status Transitions

```
pending
  |
  ├─> in-progress ─> completed
  |
  └─> cancelled
```

- **pending**: Order created, awaiting preparation
- **in-progress**: Order is being prepared
- **completed**: Order fulfilled and delivered
- **cancelled**: Order cancelled before completion

### Payment Status Flow

```
unpaid -> paid -> refunded
```

- **unpaid**: Order created but not paid
- **paid**: Payment received
- **refunded**: Payment refunded to customer

### Table Status Management

```
available <-> occupied
    |
    v
reserved
```

- **available**: Table is free for new orders
- **occupied**: Table has an active order (auto-updated)
- **reserved**: Table is reserved for future use

Tables automatically update to "occupied" when an order is created and return to "available" when the order is completed or deleted.

### Automatic Calculations

When an order is created or updated:
1. Order items are linked to menu items
2. Each item's price is locked from the menu item
3. Item subtotal = price × quantity
4. Order subtotal = sum of all item subtotals
5. Tax = subtotal × 0.10 (10% tax rate)
6. Total = subtotal + tax

---

## Testing

### Using Postman

Import the included Postman collection:

1. Open Postman
2. Import `postman_collection.json`
3. Set the `base_url` variable to `http://localhost:8000`
4. Start with Authentication > Login
5. Copy the access_token from response
6. Set the `token` variable in Postman
7. Test other endpoints

The collection includes all endpoints with example requests.

### Using cURL

Example workflow:

```bash
# 1. Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@restaurant.com","password":"password123"}'

# 2. Save the token from response
TOKEN="your_token_here"

# 3. Create a customer
curl -X POST http://localhost:8000/api/v1/customers \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Test Customer","email":"test@example.com"}'

# 4. Create an order
curl -X POST http://localhost:8000/api/v1/orders \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "table_id": 1,
    "items": [{"menu_item_id": 1, "quantity": 2}]
  }'

# 5. Get all orders
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/v1/orders
```

### Using PHPUnit

Run the test suite:

```bash
php artisan test
```

---

## Troubleshooting

### Database Connection Issues

If you encounter database connection errors:

1. Verify credentials in `.env` file
2. Ensure MySQL/PostgreSQL service is running
3. Check database exists: `SHOW DATABASES;`
4. Verify user has proper permissions

### Authentication Errors

Common authentication issues:

1. **401 Unauthorized**: Token missing or invalid
   - Ensure token is in Authorization header
   - Format: `Authorization: Bearer {token}`
   
2. **Token expired**: Re-login to get fresh token
   
3. **Invalid credentials**: Check email and password

### Migration Errors

If migrations fail:

1. Clear configuration cache: `php artisan config:clear`
2. Check database user permissions
3. Reset migrations if needed: `php artisan migrate:fresh`
4. Re-seed data: `php artisan db:seed`

### Validation Errors

API returns 422 status with error details:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message here"]
  }
}
```

Common validation issues:
- Missing required fields
- Invalid data types
- Foreign key constraints (non-existent IDs)
- Unique constraint violations

### Debug Mode

For detailed error messages during development, set in `.env`:

```
APP_DEBUG=true
```

Check logs at `storage/logs/laravel.log`

---

## Project Structure

```
gestion-restaurant-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php       # Authentication endpoints
│   │   │   ├── OrderController.php      # Order management
│   │   │   ├── MenuItemController.php   # Menu management
│   │   │   ├── CustomerController.php   # Customer management
│   │   │   └── TableController.php      # Table management
│   │   └── Requests/
│   │       ├── StoreOrderRequest.php
│   │       ├── UpdateOrderRequest.php
│   │       ├── StoreMenuItemRequest.php
│   │       ├── UpdateMenuItemRequest.php
│   │       ├── StoreCustomerRequest.php
│   │       ├── UpdateCustomerRequest.php
│   │       ├── StoreTableRequest.php
│   │       └── UpdateTableRequest.php
│   └── Models/
│       ├── Customer.php                  # Customer model
│       ├── Table.php                     # Table model
│       ├── MenuItem.php                  # MenuItem model
│       ├── Order.php                     # Order model
│       ├── OrderItem.php                 # OrderItem model
│       └── User.php                      # User model
├── database/
│   ├── migrations/
│   │   ├── 2019_12_14_000001_create_personal_access_tokens_table.php
│   │   ├── 2024_01_01_000001_create_customers_table.php
│   │   ├── 2024_01_01_000002_create_tables_table.php
│   │   ├── 2024_01_01_000003_create_menu_items_table.php
│   │   ├── 2024_01_01_000004_create_orders_table.php
│   │   └── 2024_01_01_000005_create_order_items_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php            # Master seeder
│       ├── CustomerSeeder.php            # Sample customers
│       ├── TableSeeder.php               # Sample tables
│       └── MenuItemSeeder.php            # Sample menu items
├── routes/
│   └── api.php                           # API routes
├── config/
│   ├── auth.php                          # Authentication config
│   └── sanctum.php                       # Sanctum config
├── postman_collection.json               # Postman API collection
└── README.md                             # This file
```

---

## Technologies

### Core Framework

- **Laravel 12.x**: Modern PHP framework
- **PHP 8.2+**: Latest PHP version with type safety

### Database

- **MySQL**: Primary database support
- **PostgreSQL**: Alternative database support
- **Eloquent ORM**: Object-relational mapping

### Authentication

- **Laravel Sanctum**: Token-based API authentication
- **Bearer tokens**: Stateless authentication

### Development Tools

- **Composer**: Dependency management
- **Artisan**: Command-line interface
- **PHPUnit**: Testing framework

### API Design

- **RESTful**: Resource-based architecture
- **JSON**: Request and response format
- **HTTP**: Standard methods (GET, POST, PUT, DELETE)

---

## Best Practices

This API follows Laravel and industry best practices:

1. **RESTful Design**: Resource-based endpoints with proper HTTP methods
2. **Validation**: Form Request classes for input validation
3. **Authentication**: Secure token-based authentication
4. **Relationships**: Eloquent relationships for data integrity
5. **Error Handling**: Consistent JSON error responses
6. **Status Codes**: Proper HTTP status codes (200, 201, 401, 404, 422)
7. **Versioning**: API versioned at /api/v1/
8. **Eager Loading**: Prevent N+1 queries with relationship loading
9. **Pagination**: Automatic pagination for large datasets (15 per page)
10. **Security**: CSRF protection, SQL injection prevention, password hashing

---

## Response Formats

### Success Response

```json
{
  "id": 1,
  "name": "Example",
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### Error Response

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": [
      "The field name is required."
    ]
  }
}
```

### Paginated Response

```json
{
  "current_page": 1,
  "data": [...],
  "first_page_url": "http://localhost:8000/api/v1/orders?page=1",
  "from": 1,
  "last_page": 3,
  "last_page_url": "http://localhost:8000/api/v1/orders?page=3",
  "next_page_url": "http://localhost:8000/api/v1/orders?page=2",
  "path": "http://localhost:8000/api/v1/orders",
  "per_page": 15,
  "prev_page_url": null,
  "to": 15,
  "total": 42
}
```

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success - Request completed |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid request format |
| 401 | Unauthorized - Authentication required or failed |
| 404 | Not Found - Resource does not exist |
| 422 | Validation Error - Input validation failed |
| 500 | Server Error - Internal server error |

---

## Contributing

Contributions are welcome. Please follow these guidelines:

1. Follow Laravel best practices and conventions
2. Follow PSR-12 coding standards
3. Write clear commit messages
4. Add tests for new features
5. Update documentation as needed

---

## License

This project is licensed under the MIT License.

---

## Support

For questions or issues:

1. Check this documentation thoroughly
2. Review Laravel documentation: https://laravel.com/docs
3. Check logs: `storage/logs/laravel.log`
4. Use Postman collection for testing
5. Verify environment configuration in `.env`

---

**Built with Laravel 12.x**
