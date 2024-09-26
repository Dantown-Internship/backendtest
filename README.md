# Checker Marker System

This is a simple but over-engineered application for Wallet Management. Built with PHP, Laravel, and TailwindCSS, it incorporates several design patterns such as Repository Pattern, Service Classes, Action Classes, Interfaces to ensure maintainability and scalability.

## Technologies

- PHP
- Laravel
- TailwindCSS

## Installation

1. **Install Dependencies:**
    - Run the command "composer install" in your command terminal. Note: Make sure you have the composer binary installed on your computer

2. **Set up MySQL Database Credential in .env file**:
    - Fill in the appropriate DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME and DB_PASSWORD values. Note: Make sure you have a MySQL service installed and running on your computer.

3. **Generate Application Key**:
    - Run the command "php artisan key:generate" in your command terminal

4. **Run database migrations**:
    - Run the command "php artisan migrate" in your command terminal

6. **Serve the application**:
    - Run the command "php artisan serve" in your command terminal

5. **Visit Application URL**:
    - Go to your web browser and visit https://localhost:8000

