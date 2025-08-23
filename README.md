# Library Management System

A full-stack library management application built with Laravel 12 backend and React 19 frontend, featuring user authentication, book management, author tracking, and loan management with comprehensive unit testing.

## 🚀 Features

- **User Authentication**: Secure login/logout with Laravel Sanctum
- **Book Management**: CRUD operations for books with author relationships
- **Author Management**: Complete author profiles with book associations
- **Loan System**: Track book borrowing and returns with due dates
- **Responsive UI**: Modern React interface with Tailwind CSS
- **Comprehensive Testing**: 98 unit tests with 369 assertions
- **RESTful API**: Clean API design with proper authentication

## 🏗️ Architecture

### Backend (Laravel 12)
- **Framework**: Laravel 12 with PHP 8.2+
- **Authentication**: Laravel Sanctum for API tokens
- **Database**: MySQL/PostgreSQL with migrations and seeders
- **Testing**: PHPUnit with comprehensive test coverage
- **API**: RESTful endpoints with JSON responses

### Frontend (React 19)
- **Framework**: React 19 with TypeScript
- **Styling**: Tailwind CSS for modern UI
- **State Management**: React Query for API state
- **Routing**: React Router for navigation
- **HTTP Client**: Axios for API communication

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.2 or higher**
- **Composer** (PHP package manager)
- **Node.js 18 or higher**
- **npm** or **yarn**
- **MySQL 8.0+** or **PostgreSQL 13+**
- **Git**

## 🛠️ Installation & Setup

### 1. Clone the Repository

```bash
git clone <your-repository-url>
cd SPROBE_ASSESSMENT
```

### 2. Backend Setup (Laravel)

```bash
# Navigate to backend directory
cd backend

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
# Update these values in your .env file:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=library_management
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run database migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed

# Create storage link for file uploads (if needed)
php artisan storage:link

# Start the Laravel development server
php artisan serve
```

The backend will be available at `http://localhost:8000`

### 3. Frontend Setup (React)

```bash
# Open a new terminal and navigate to frontend directory
cd frontend

# Install Node.js dependencies
npm install

# Create environment file
cp .env.example .env.local

# Configure API base URL in .env.local
# NEXT_PUBLIC_API_BASE_URL=http://localhost:8000/api

# Start the React development server
npm run dev
```

The frontend will be available at `http://localhost:3000`

## 🧪 Running Tests

### Backend Tests
```bash
cd backend

# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run tests with coverage (requires Xdebug or PCOV)
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/AuthTest.php
```

### Frontend Tests
```bash
cd frontend

# Run tests
npm test

# Run tests in watch mode
npm run test:watch
```

## 📚 API Documentation

### Authentication Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register a new user |
| POST | `/api/login` | Login user |
| POST | `/api/logout` | Logout user |
| GET | `/api/get-user` | Get current user profile |

### Book Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/books` | List all books |
| POST | `/api/books` | Create a new book |
| GET | `/api/books/{id}` | Get specific book |
| PUT | `/api/books/{id}` | Update book |
| DELETE | `/api/books/{id}` | Delete book |

### Author Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/authors` | List all authors |
| POST | `/api/authors` | Create a new author |
| GET | `/api/authors/{id}` | Get specific author |
| PUT | `/api/authors/{id}` | Update author |
| DELETE | `/api/authors/{id}` | Delete author |
| GET | `/api/authors/{id}/books` | Get author's books |

### Loan Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/loans` | List user's loans |
| POST | `/api/loans` | Create a new loan |
| GET | `/api/loans/{id}` | Get specific loan |
| PUT | `/api/loans/{id}` | Update loan |
| DELETE | `/api/loans/{id}` | Delete loan |
| PATCH | `/api/loans/{id}/complete` | Complete loan |

### Loan Item Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/loan-items` | List all loan items |
| POST | `/api/loan-items` | Create a new loan item |
| GET | `/api/loan-items/{id}` | Get specific loan item |
| PUT | `/api/loan-items/{id}` | Update loan item |
| DELETE | `/api/loan-items/{id}` | Delete loan item |
| PATCH | `/api/loan-items/{id}/return` | Return loan item |

## 🔐 Authentication

All API endpoints (except register and login) require authentication. Include the Bearer token in the Authorization header:

```bash
Authorization: Bearer <your-token>
```

## 📁 Project Structure

```
SPROBE_ASSESSMENT/
├── backend/                 # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   └── Providers/
│   ├── database/
│   │   ├── migrations/
│   │   ├── seeders/
│   │   └── factories/
│   ├── routes/
│   └── tests/
├── frontend/               # React Application
│   ├── src/
│   │   ├── app/
│   │   ├── components/
│   │   └── lib/
│   ├── public/
│   └── package.json
└── README.md
```