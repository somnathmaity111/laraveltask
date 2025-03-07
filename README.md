<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# User Management API & Scheduled Fetcher

## About This Project
This Laravel 10 project:
- Fetches random users from an external API every 5 minutes.
- Stores users in a relational database.
- Provides a REST API with filtering, field selection, and pagination.

## Project Setup
### Prerequisites
Ensure the following are installed on your system:
- PHP 8.1+
- Composer
- Laravel 10
- MySQL (or any preferred database)
- cURL (for HTTP requests)

### Installation
Run the following commands to set up the project:
```sh
git clone <repository-url>
cd project-folder
composer install
cp .env.example .env
php artisan key:generate
```

### Configure Environment
Edit the `.env` file and update the database settings:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

GET_USERS_API_LINK=your_api_link
```

Run migrations to create database tables:
```sh
php artisan migrate
```

## Scheduled Task (User Fetching)
A scheduled task fetches **5 users every 5 minutes** from `your_api_link` and stores them in the database.

Run the scheduler manually:
```sh
php artisan schedule:work
```

To automate, add this to the **cron job** (Linux/macOS):
```sh
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

## API Endpoints
### Get Users
#### Endpoint:
```sh
GET /api/users
```

#### Query Parameters:

| Parameter  | Type    | Description |
|------------|--------|-------------|
| `gender`   | string | Filter by gender (`male`, `female`). |
| `city`     | string | Filter by city. |
| `country`  | string | Filter by country. |
| `fields`   | string | Specify fields (`name,email,gender,city,country`). |
| `paginate` | boolean | Enable/disable pagination. Default: `true`. |
| `per_page` | integer | Number of records per page (only if `paginate=true`). |

#### Examples:
✔ Get all users (paginated by default):
```
GET http://127.0.0.1:8000/api/users
```

✔ Get only `name` and `email`:
```
GET http://127.0.0.1:8000/api/users?fields=name,email
```

✔ Filter by gender:
```
GET http://127.0.0.1:8000/api/users?gender=male
```

✔ Disable pagination:
```
GET http://127.0.0.1:8000/api/users?paginate=false
```

## Testing
### Clear Cache Before Testing
```sh
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan optimize
```

### Run Laravel Server
```sh
php artisan serve
```


## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

