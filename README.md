# Booking Layer Test Solution

## Requirements

- PHP 8.0
- PostgreSQL for database connection.
- Redis for queue but can also use database connection.

## How to install

- Install dependencies `composer install`.
- Copy `.env.example` to `.env` and setup database and queue connection.
- Migrate tables to database `php artisan migrate`.

## How to run

- Run locally using `php artisan serve`.
- Run queue worker `php artisan queue:work`.
- Run `php artisan test --testsuite=Unit --coverage-html reports/` to get latest unit test coverage.

## Implementations

- SOLID
- Unit Testing
- Queue
- Event Listener
- Cache
- Modular programming
- Repository pattern

## Details

- Postman is in the root folder in the `Booking Layer Test.postman_collection.json` file.
- Most business logic/domain code is placed in `src/Acme` folder.
- All business domain is structured to modules which will contain its related components such as `Model`, `Repository`, `Service`, `EventListeners`, etc.
- All modules have its own `Module` class that extends Laravel's service provider and is responsible for registering the repositories/services to Laravel.
