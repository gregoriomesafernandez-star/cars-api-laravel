# Cars API Laravel

REST API developed with Laravel featuring JWT authentication and a complete CRUD system for car management.

## Frontend Repository

[Cars Angular](https://github.com/gregoriomesafernandez-star/cars-angular)

## Features

- User registration
- User login with JWT authentication
- User identity retrieval
- Create cars
- List cars
- View car details
- Update cars
- Delete cars
- Input validation
- User-Car relationship
- JSON API responses

## Technologies

- Laravel 12
- PHP
- MySQL
- JWT Authentication
- REST API
- Eloquent ORM

## Installation

Clone the repository:

```bash
git clone https://github.com/gregoriomesafernandez-star/cars-api-laravel.git
cd cars-api-laravel
```

Install dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

## Database

Configure your database in the `.env` file.

Run migrations:

```bash
php artisan migrate
```

This command will automatically create all required database tables.

Start the development server:

```bash
php artisan serve
```

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|----------|----------|-------------|
| POST | /api/register | Register user |
| POST | /api/login | Login user |

### Cars

| Method | Endpoint | Description |
|----------|----------|-------------|
| GET | /api/cars | List all cars |
| GET | /api/cars/{id} | Get car details |
| POST | /api/cars | Create car |
| PUT | /api/cars/{id} | Update car |
| DELETE | /api/cars/{id} | Delete car |

## Authentication

Protected routes require a JWT token in the Authorization header:

```http
Authorization: your_jwt_token
```

## Author

Gregorio Jose Mesa Fernandez
