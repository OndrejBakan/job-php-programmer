
# simplo-sro/job-php-programmer

## Overview

This is my take at the [simplo-sro/job-php-programmer](https://github.com/simplo-sro/job-php-programmer) task. The original assignment is:

> Create a simple Laravel application that would serve as an API. The application will be used to manage customers. The proposed API should meet all REST principles and use JSON for data exchange.
> 
> Do not use any third party packages.
>
> Application will consist of following entities:
>### Customer
> A particular customer. For this entity, create end-points for CRUD operations according to the REST convention. Choose whatever attributes you like.
> ### CustomerGroup
> A customer group. Customer can belong to 0 - N customer groups. You do not need to create a separate resource for this entity. Preparing the data before hand is enough, for example using a seed.
> ### Functionality requirements
> - Find a suitable way to create a relation between a customer and a customer group (POST/PUT on the user resource).
> - Find a suitable way to optionally request customer's customer groups data along with the user (GET on the user resource).
> - Inputs must be validated.
> Use PORTO architecture (and Repository Pattern, optionally) for bonus points.

## Installation

- Clone this repository:

  `git clone https://github.com/OndrejBakan/job-php-programmer.git`

- Switch to the project folder:

  `cd job-php-programmer`

- Install dependencies using composer:

  `composer install`

- Copy .env file:

  `cp .env.example .env`

- Change these lines to use SQLite for quickstart:

  ```
  DB_CONNECTION=sqlite
  DB_DATABASE=..\database\database.sqlite
  ```

- Generate application key:

  `php artisan key:generate`

- Run database migrations:

  `php artisan migrate`

- Run database seeders:

  `php artisan db:seed`

- Start the local development server:

  `php artisan serve`

## Technologies
- laravel/framework
- laravel/pint
- [Debugbar for Laravel](https://github.com/barryvdh/laravel-debugbar)
