
# Contact Management Application

This is a full-stack contact management application built with:

- **Backend:** Laravel 12 (RESTful API with Sanctum authentication)
- **Frontend:** Vue 3 + Vite + Pinia + Bootstrap 5
- **Authentication:** Token-based using Laravel Sanctum

---

## Project Structure

```
/contact-backend     --> Laravel API
/contact-frontend    --> Vue 3 frontend
```

---

## Features

- User Registration & Login
- Add, Edit, Delete Contacts
- Paginated Contact List
- Modular Vue 3 Components
- Token-based Secure API with Sanctum
- Responsive UI with Bootstrap

---

## Tech Stack

| Layer       | Tech Used                                  |
|-------------|---------------------------------------------|
| Frontend    | Vue 3, Vite, Pinia, Axios, Bootstrap 5      |
| Backend     | Laravel 11, Sanctum, MySQL (or SQLite)      |
| Auth        | Laravel Sanctum (SPA authentication)        |
| Deployment  | Vite build (frontend) + Laravel API         |

---

## Installation

### 1. Backend (Laravel)

```bash
cd contact-backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

> Laravel API will run at `http://localhost:8000`

### 2. Frontend (Vue 3)

```bash
cd contact-frontend
cp .env.example .env
npm install
npm run dev
```

> Vue App will run at `http://localhost:5173`

---

## API Authentication Setup

Make sure Sanctum is configured in Laravel:

- Add this to `config/cors.php`:
```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'supports_credentials' => true,
```
- If cors.php file not in your project run 

```bash
php artisan config:publish cors
```


- Sanctum routes are already registered in Laravel `routes/api.php`.

---

## API Endpoints

| Method | Endpoint             | Description            |
|--------|----------------------|------------------------|
| POST   | /register            | Register user          |
| POST   | /login               | Login user             |
| POST   | /logout              | Logout user            |
| GET    | /contacts            | List contacts (paginated) |
| POST   | /contacts            | Create contact         |
| PUT    | /contacts/{id}       | Update contact         |
| DELETE | /contacts/{id}       | Delete contact         |

---

## Environment Configs

### Frontend `.env`:

```env
VITE_API_BASE_URL=http://localhost:8000/api
```

### Backend `.env`:

```env
APP_URL=http://localhost:8000
SESSION_DOMAIN=localhost
```

## Best Practices Followed

- Clean, modular component architecture
- Laravel uses SOLID, KISS principles
- Validation at both client and server side
- Separation of concerns: Frontend/UI & Backend/API
- Pinia store for global state management
- Proper error handling and loading states
- Custom API responses for frontend-friendly messages

---

## Testing

Run backend tests:

```bash
php artisan test
```

