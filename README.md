# POS Cafe System

POS Cafe System is a modern web-based Point of Sale (POS) application built using Laravel and Livewire. This system is designed to help cafés, restaurants, or small food businesses manage orders, menus, kitchen workflows, and sales reports efficiently.

The application focuses on speed, simplicity, and a clean dashboard interface that supports daily cashier and kitchen operations.

## Features

- Order Management  
  Cashiers can create orders quickly through an interactive POS interface. The system automatically calculates subtotal, tax, discount, total payment, and change.

- Menu Management  
  Administrators can add, update, delete, and organize menu items. Each menu includes details such as name, category, price, description, and image.

- Kitchen Display System (KDS)  
  Kitchen staff can monitor incoming orders in real time. Orders appear automatically after being confirmed by the cashier, allowing kitchen staff to manage preparation efficiently.

- Real-time Order Status  
  Kitchen staff can update the order status such as Pending, Preparing, and Ready, and the cashier interface will reflect the status changes.

- Sales Report  
  The system provides reports such as daily sales, weekly sales, monthly sales, and best-selling menu items to help analyze business performance.

- Dashboard Analytics  
  The dashboard displays key business metrics including total orders, daily revenue, and popular menu items.

- Inventory Tracking (optional module)  
  Track stock usage and manage inventory levels for menu ingredients.

- Responsive Dashboard Interface  
  Designed with a modern dashboard layout optimized for cashier and operational workflows.

## Technology Stack

- Backend: Laravel  
- Frontend: Laravel Livewire  
- Styling: TailwindCSS  
- Database: MySQL  
- Real-time updates: Livewire polling or broadcasting

## System Modules

- Authentication and Role Management
- POS Order Interface
- Menu Management
- Kitchen Display System
- Order Tracking
- Sales Reporting
- Dashboard Analytics

## Project Structure

The application follows a modular Laravel structure to maintain clean architecture and scalable code.

```
app/
 ├── Livewire
 │   ├── POS
 │   ├── Menu
 │   ├── Kitchen
 │   ├── Orders
 │   └── Dashboard
 │
 ├── Services
 │   ├── OrderService
 │   ├── MenuService
 │   └── ReportService
 │
 ├── Models
 └── Http
     └── Requests
```

Business logic is placed inside service classes while Livewire components handle UI interaction.

## Installation

Clone the repository

```
git clone https://github.com/yourusername/pos-cafe-system.git
```

Go to the project directory

```
cd pos-cafe-system
```

Install dependencies

```
composer install
```

Copy environment file

```
cp .env.example .env
```

Generate application key

```
php artisan key:generate
```

Configure your database in `.env` file.

Run migrations

```
php artisan migrate
```

Start the development server

```
php artisan serve
```

## Usage

1. Login to the system.
2. Add menu items from the Menu Management page.
3. Use the POS interface to create customer orders.
4. Confirm orders so they appear in the Kitchen Display System.
5. Kitchen staff can update order status during preparation.
6. View sales performance from the reporting dashboard.

## Future Improvements

- QR ordering system
- Multi-outlet support
- Offline mode for POS
- Customer loyalty system
- Receipt printing integration

## License

This project is open-source and available under the MIT License.

## Author

Developed by Nadhif Fauzil