# E-Commerce Platform Documentation

## Overview

This project is a production-ready eCommerce platform built using **Laravel 12**. It follows a modular and service-oriented architecture, separating responsibilities between the **Web (customer-facing)** and **Admin (management)** panels.

---

## Tech Stack

### Server-Side

- **Language:** PHP
- **Framework:** Laravel 12

### Client-Side

- **Languages:**
    - HTML5
    - CSS3
    - JavaScript

- **Frameworks & Libraries:**
    - Alpine.js
    - Tailwind CSS

---

## Application Architecture

The application is divided into two main parts:

### 1. Web Application

Customer-facing interface responsible for browsing products and placing orders.

### 2. Admin Panel

Management interface for controlling products, orders, and system configurations.

---

## Admin Panel

- Built using **Filament v4**
- Access route:

/admin/login

### Features

- Product Management
- Category Management
- Routine Management
- Order Settings
- Business Information Management
- Content Management System (CMS)

### Implementation

- Uses **Filament Resource Classes** to manage:
- Products
- Categories
- Routines

---

## Web Application

### Structure

- Controllers and Request classes are organized under the `Web` namespace.
- Business logic is handled using **Action classes** and **Service classes**.

### Core Features

- Add to Cart
- Remove from Cart
- Checkout Process

---

## Service Layer

The project uses the **Service Pattern** to encapsulate business logic.

### Services

#### CartService

Handles all cart-related operations:

- Add items to cart
- Remove items from cart
- Manage cart state

#### OrderService

Responsible for:

- Order creation
- Order processing logic

---

## Authentication

- Implemented using **Laravel Fortify**
- Handles:
- User login
- Registration
- Authentication flows

---

## Background Jobs & Queues

### Queue System

- Used for handling asynchronous tasks.

### Current Usage

- Sending emails (e.g., order confirmations)

### Queue Execution (Production)

- The application relies on an external cron job service:

https://cronjob.con

- A cron job runs every minute to process the queue worker:

```bash
php artisan queue:work
```
