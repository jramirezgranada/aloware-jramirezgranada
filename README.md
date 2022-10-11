### System Requirements

1. Docker
2. Local PHP 8 and Composer

### Installation Steps

- Clone this repository
- Create `.env` file: `cp .env.example .env`
- Install dependencies `composer install`
- Generate system key: `php artisan key:generate`
- Install laravel sail: `php artisan sail:install` and enter 0 to install mysql
- Create development environment: `vendor/bin/sail up -d`
- Run migrations and seeders: `vendor/bin/sail artisan migrate:fresh --seed`
- Run tests `vendor/bin/sail test`
- Import Aloware.postman_collection.json into postman this has the request to use this API

### Tech Stack

- Laravel 9
- PHP 8
- MySQL
- Sail

### Author

Jorge Andres Ramirez Granada

[jramirezgranada@gmail.com](mailto:jramirezgranada@gmail.com)

