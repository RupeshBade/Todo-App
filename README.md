# Laravel To-Do Application

A Laravel-based To-Do application with authentication, CRUD operations, database connectivity, and custom package integration.

## Features

- User Registration
- User Login
- User Logout
- Task Creation
- Task Editing
- Task Deletion
- Task Completion Toggle
- User-specific Task Ownership
- MySQL Database Integration
- JWT Token Generation using Custom Package
- JetConverter Custom Package Integration

## Tech Stack

- Laravel 12/13
- PHP
- MySQL
- Laravel Breeze
- Blade Templates

## Authentication

Authentication is implemented using Laravel Breeze.

Users can:

- Register
- Login
- Logout

Each user can only access and manage their own tasks.

## Custom Packages

### JetConverter

Processes task titles before storing them in the database.

### JwtConverter

Generates and validates JWT tokens for task synchronization.

## Installation

```bash
git clone https://github.com/RupeshBade/Todo-App.git

cd Todo-App

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

npm install

npm run build

php artisan serve
```

## Author

Rupesh Bade
BCA Student
Kathford International College