<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>


# 🏪 Shop Management System (SMS)

A modern **Shop Management System** built with **Laravel 10, Livewire, MySQL, and Bootstrap**, designed to help small and medium businesses manage **sales, purchases, inventory, expenses, customers, suppliers, and reports** efficiently.

---

## 📌 Table of Contents
1. [Features](#-features)
2. [Installation](#-installation)
3. [Usage](#-usage)
4. [Project Structure](#-project-structure)
5. [Tech Stack](#-tech-stack)
6. [Roadmap](#-roadmap)
7. [Contribution](#-contribution)
8. [License](#-license)

---

## 🚀 Features

### 🔐 User & Role Management
- Admin, Manager, and Sales roles
- Role-based access control
- Secure login & authentication

### 📦 Product & Inventory Management
- Product categories
- Stock quantity tracking
- Purchase & sale price management
- Low stock alerts

### 🛒 Sales Management
- Create, edit, and view sales
- Dynamic product rows (quantity, price, subtotal)
- Discounts, shipping, paid & due calculation
- Auto stock deduction
- Invoice generation

### 📥 Purchase Management
- Supplier-based purchases
- Stock auto-increment
- Purchase history & reports

### 👥 Customer & Supplier Management
- Customer profiles & transaction history
- Supplier records & purchase tracking

### 💰 Expense Management
- Expense categories
- Date-wise and category-wise tracking

### 📊 Reports & Analytics
- Daily, weekly, monthly reports
- Profit & Loss statements
- Sales vs Purchase vs Expense
- Stock movement reports

### 🔍 Smart Listing System
- Universal search across all pages
- Dynamic per-page selector (10–All)
- Live pagination with Livewire
- Reusable table components

### 🧾 Invoice & Accounting
- Auto invoice numbers
- Payment tracking
- Due management
- Partial payments support

---

## ⚙️ Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/shop-management-system.git
cd shop-management-system

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Setup environment file
cp .env.example .env
php artisan key:generate

