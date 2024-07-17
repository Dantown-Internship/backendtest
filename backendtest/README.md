<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
 

## Maker-Checker System with Wallet Management
This project is a complete solution for a technical test on the Maker-Checker system with a wallet management system. The project uses Laravel Breeze for authentication, Bootstrap for the frontend with a minimalistic design, and PHPUnit for testing.

### Features

- **Authentication:**
    - Implemented using Laravel Breeze.
- **Frontend:**
    - Designed with Bootstrap for a simple and responsive UI.
- **Testing:**
    - Thoroughly tested using PHPUnit.

### Setup Instructions
#### Database Setup

1 **Create Databases:**
- **Main Database:**
    - backendtest_db
- **Testing Database:**
    - test_backendtest_db

#### Project Setup

- **Pull from the repo:** 
    - git pull   or git clone https://github.com/Dantown-Internship/backendtest.git
- **Checkout to my Branch:** 
    - git checkout isaac-toyin  
- **Navigate to Project Directory:**
    - cd backendtest 
- **Copy Environment File:** 
    - cp .env.example .env     
- **Configure Enviromental variable:**      
    - Update the .env file with your mail server settings.
        DB_DATABASE=backendtest_db
        DB_USERNAME=your_username
        DB_PASSWORD=your_password
        MAIL_MAILER=smtp
        MAIL_HOST=your_mail_host
        MAIL_PORT=your_mail_port
        MAIL_USERNAME=your_mail_username
        MAIL_PASSWORD=your_mail_password
        MAIL_ENCRYPTION=your_mail_encryption
- **Install Dependencies:**      
    - composer install
    - npm install
- **Run Migrations and Seed Database:**      
    - php artisan migrate --seed
 

### Running Tests
- **Update Testing Environment File**      
    - Edit the .env.testing file with the testing database name (test_backendtest_db).
    - DB_DATABASE=test_backendtest_db
- **Run Tests:**      
    - php artisan test --env=testing
 

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
