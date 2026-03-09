
# FlowServe: Modern POS System

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-FB70A9?style=for-the-badge&logo=livewire&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC34A?style=for-the-badge&logo=alpine.js&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**FlowServe** is a modern **web-based Point of Sale (POS) system** designed specifically for cafés and restaurants. Built with the **TALL Stack (Tailwind, Alpine.js, Laravel, Livewire)**, FlowServe provides high operational speed with an intuitive interface for cashiers, kitchen staff, and business owners.

---

## Key Features

### 🛒 1. Smart POS Interface
Process transactions quickly with an interactive interface. Automatic calculation of subtotal, tax, discounts, and change in a single responsive screen.

### 🍳 2. Kitchen Display System (KDS)
Say goodbye to paper order tickets. The Kitchen Display System allows kitchen staff to monitor orders in **real time**. Once the cashier confirms payment, the order instantly appears on the kitchen screen with a time indicator.

**Order Status Tracking:**
Pending ➡️ Preparing ➡️ Ready

### 📱 3. QR Table Generator
Improve efficiency with a self-ordering system. Admins can generate **unique QR codes for each table**, allowing customers to scan and instantly access the digital menu.

### 📦 4. Inventory Management
Manage raw material stock precisely. The system tracks inventory usage based on sold menu items and provides automatic alerts when stock is running low.

### 📊 5. Revenue Reporting
Analyze your business performance with accurate data:

- Revenue Reports: Daily, weekly, and monthly sales reports
- Best Seller Analysis: Identify the most popular menu items
- Dashboard Analytics: Sales trend visualizations for better decision-making

---

## 🛠️ Tech Stack

| Layer | Technology |
|------|------------|
| Backend | Laravel 12.x |
| Frontend |  Livewire 4
| Styling | TailwindCSS 4|
| Database | MySQL / PostgreSQL |
| Cache | Redis (Recommended) |

---

## 📂 Project Structure

FlowServe uses a **Service Layer Architecture** to separate business logic from the UI, keeping the codebase clean, modular, and easily testable.

```text
app/
 ├── Livewire/          # Interactive UI Components (POS, KDS, Reports)
 ├── Services/          # Business Logic (OrderService, InventoryService, ReportService)
 ├── Models/            # Database Models & Relationships
 └── Http/              # Controllers & Form Requests
```
---

## ⚙️ Quick Installation

Follow these steps to run **FlowServe** locally.

### 1. Requirements

Make sure the following tools are installed:

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL or PostgreSQL

---

### 2. Clone the Repository

```bash
git clone https://github.com/NadhifFauzilAdhim/FlowServePos.git  
cd FlowServePos
```

---

### 3. Install Dependencies

```bash
composer install  
npm install
```

---

### 4. Configure Environment

```bash
cp .env.example .env
```

Then configure your database credentials:

DB_DATABASE=  
DB_USERNAME=  
DB_PASSWORD=

---

### 5. Generate Application Key

```bash
php artisan key:generate
```

---

### 6. Run Database Migrations

```bash
php artisan migrate
```

---

### 7. Start the Development Server

```bash
php artisan serve

npm run dev
```

---

### 8. Access the Application

Demo account:  
Email: admin@lumina.cafe  
Password: Password

---


## 🤝 Contribution

You are welcome to **fork, modify, and build upon this project** for personal or commercial use.

However, as a form of support for the original work, please **keep the original credit to the FlowServe project and the author** somewhere in your project.

This small acknowledgment helps support the development of open-source tools and encourages further improvements to the project.

Thank you for supporting the project.


## 📄 License

This project is licensed under the **MIT License**.