# Local Setup

### Requirements
* PHP, MySQL, and Git

### Setup
clone the repository
```bash
$ git clone https://github.com/Dantown-Internship/backendtest.git
```

install dependencies
```bash
$ composer install
$ npm install
```

Then, create a `.env` file based on `.env.example`
```bash
$ cp .env.example .env
```

set up database
   - create a new database
   - fill the details in .env file

set up mail account
   - use mailtrap.io for local mailing
   - set up your smtp account
   - get the smtp info and fill the .env file

run migration and seeder
```bash
$ php artisan migrate
$ php artisan db:seed
```

start the server
```bash
$ php artisan serve
$ npm run dev
```
Admin login details
- Email: admin@dantown.com
- Password: password

