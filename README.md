# Event Management System – Backend (Laravel)

This Laravel backend powers a Vue SPA for event management: events, venues, tickets, orders, coupons, dashboards, and authentication.

## Features
- Authentication with Laravel Sanctum and Fortify
- CRUD for Events, Venues, Ticket Types, Orders, Tickets, Coupons
- Dashboard stats endpoints
- API-first backend for SPA
- Git workflows: GitHub Flow / Gitflow, Conventional Commits

## Requirements
- PHP 8.2+
- Composer 2+
- SQLite (dev) or MySQL/PostgreSQL (production)
- Node.js 18+ for asset build (frontend assets if needed)

## Getting Started
1. Install dependencies:
   - Composer: `composer install`
   - Node (optional for assets): `npm install`
2. Environment:
   - Copy `.env.example` to `.env` and update values.
   - Generate key: `php artisan key:generate`
3. Database:
   - Migrate: `php artisan migrate`
   - Seed (optional): `php artisan db:seed`
4. Serve API:
   - `php artisan serve` (default http://127.0.0.1:8000)

## Testing
- Run PHPUnit tests: `./vendor/bin/phpunit`

## API
Full API documentation is available at `docs/api.md`.

## Git & Branching
- Conventional Commits: https://www.conventionalcommits.org/
- Branching strategies supported:
  - GitHub Flow: `main` + feature branches
  - Gitflow: `develop` + feature branches

### Automation Script
`setup.sh` automates:
- Repo initialization, remotes
- Feature branches per module
- Scaffolding (models, controllers, migrations, factories, seeders, tests)
- Conventional commits and pushes

Usage:
```
chmod +x setup.sh
./setup.sh            # all features
./setup.sh --only user-module
```

## Project Structure (key paths)
- `app/Http/Controllers/Api/V1/` – API controllers
- `app/Models/` – Eloquent models
- `database/migrations/` – Migrations
- `database/factories/` – Factories
- `database/seeders/` – Seeders
- `routes/api.php` – API routes
- `tests/` – Tests

## Security
- Uses `auth:sanctum` for protected routes
- CORS middleware for SPA integration

## License
Proprietary. All rights reserved (update as appropriate).
