## Maker Checker System Documentation

### Overview

This application manages user transactions with functionality for creating, viewing, approving, and rejecting transactions. It includes notifications for users when their transactions are approved or rejected.

### Features

1. User Authentication: Secure access for authenticated users.
2. Transaction Management:
   - Create Transactions
   - View Transactions
   - Approve Transactions (accessible by users with the `checker` role)
   - Reject Transactions (accessible by users with the `checker` role)
   Note: Menu dropdown contains all navigation link.
3. Notifications: Users receive notifications via email when their transactions are approved or rejected.

### Requirements

- Laravel 10.x
- PHP 8.x
- Database (MySQL)
- Mail server configuration

### Setup Instructions

1. Clone the Repository

   ```bash
   git clone https://github.com/repo
   cd transaction-management
   ```

2. Install Dependencies

   ```bash
   composer install
   npm install
   ```

3. Configure Environment

   Copy `.env.example` to `.env` and configure your database and mail settings.

   ```bash
   cp .env.example .env
   ```

   Edit the `.env` file with your configuration details.

4. Generate Application Key

   ```bash
   php artisan key:generate
   ```

5. Run Migrations

   ```bash
   php artisan migrate
   ```

6. Seed Database

   If you have seed data, you can run:

   ```bash
   php artisan db:seed
   ```

   Note: Test with the seeded data for for user with role of checker.
   'email' => 'loveday@example.com',
   'password' => ('password'),

7. Start the Development Server

   ```bash
   php artisan serve and
   npm run dev
   ```

### Routes

- GET `/transactions`: List all transactions for the authenticated user.
- GET `/transactions/create`: Display the form to create a new transaction.
- POST `/transactions`: Store a new transaction.
- GET `/transactions/pending`: List all pending transactions (accessible by `checker` role).
- POST `/transactions/{id}/approve`: Approve a transaction (accessible by `checker` role).
- POST `/transactions/{id}/reject`: Reject a transaction (accessible by `checker` role).
- GET `/wallet`: Show the user's wallet details.

### Controllers

- TransactionController: Manages transaction operations.
  - `index()`: Displays transactions for the authenticated user.
  - `create()`: Shows the form to create a transaction.
  - `store(Request $request)`: Stores a new transaction.
  - `pending()`: Shows all pending transactions.
  - `approve($id)`: Approves a transaction and updates wallet and pool balances.
  - `reject($id)`: Rejects a transaction.
- WalletController: Manages user wallet operations.

### Models

- Transaction: Represents a transaction record.
- User: Represents the authenticated user.
- Wallet: Represents the user's wallet.
- Pool: Represents a pool of funds.

### Notifications

- TransactionStatusUpdated: Notification sent via email when a transaction is approved or rejected.

### Testing

Perform the following tests:

1. Unit Tests

   ```bash
   php artisan test
   ```

### Common Issues and Troubleshooting

- RoleDoesNotExist Error: Ensure to seed the database so you have a default user with the role of checker.

### Conclusion

This documentation provides an overview of the application, including setup instructions, routes, controllers, models, and testing procedures. Follow these guidelines to configure, run, and test your application effectively.