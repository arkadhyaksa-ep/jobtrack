# JobTrack

JobTrack is a simple job application tracker built as a Laravel portfolio project. It helps keep applications, their current status, and follow-up notes in one place instead of scattered across spreadsheets and messages.

## Features

- Create, view, edit, and delete job applications.
- Record a company, job title, optional posting URL, status, application date, and notes.
- Search by company name or job title and filter by status; the filters can be combined.
- Browse newest applications first, 10 per page, with filters preserved across pages.
- View total, Interview, Offer, and Rejected counts for all applications.
- See server-side validation errors and a confirmation prompt before deleting an application.

Supported statuses are Wishlist, Applied, Interview, Offer, Rejected, and Withdrawn.

## Technology

- PHP 8.3 or later (the inspected local environment uses PHP 8.3.13).
- Laravel 13 (installed locally as 13.35.0; the project requires `^13.0`).
- Blade templates with plain CSS and a small inline JavaScript confirmation.
- SQLite by default; Laravel's database configuration also includes MySQL support.
- PHPUnit 12.5 (installed locally as 12.5.38).
- Vite and Tailwind CSS are included in the frontend toolchain. The current JobTrack pages use inline styles.

Composer 2.8.2, Node.js 25.2.1, and npm 11.6.3 were available in the inspected development environment; these versions are not pinned by the repository.

## Screenshots

Screenshots have not been added yet. Add them to the repository when available and replace this placeholder with the images and captions.

## Requirements

- PHP 8.3 or later and Composer.
- A database supported by Laravel. The checked-in default is SQLite and requires the PHP `pdo_sqlite` extension. MySQL or MariaDB can be used by configuring the MySQL connection and enabling `pdo_mysql`.
- Node.js and npm are optional for `/applications`; they are needed to build the Vite assets used by the default `/` welcome page or to work on the frontend asset pipeline.
- The SQLite PDO extension is also required to run the database-backed test suite, which uses an in-memory SQLite database.

## Installation and setup

Run these commands in PowerShell. `<JOBTRACK_REPOSITORY_URL>` is a placeholder; replace it once a Git remote for JobTrack exists.

```powershell
git clone <JOBTRACK_REPOSITORY_URL> jobtrack
cd jobtrack
composer install
Copy-Item .env.example .env
php artisan key:generate
if (-not (Test-Path database/database.sqlite)) {
    New-Item -ItemType File -Path database/database.sqlite | Out-Null
}
php artisan migrate
php artisan serve
```

Open the local address printed by `php artisan serve` and visit `/applications`.

Keep `.env` local; it is ignored by Git. `php artisan key:generate` creates an application key for a new local environment. If a valid local key is already present, do not replace it unnecessarily.

### Database configuration

The checked-in `.env.example` selects SQLite. Laravel's database configuration uses `database/database.sqlite` as the SQLite path when `DB_DATABASE` is not set. The setup commands above create that file before running migrations.

To use MySQL or MariaDB instead, create an empty database, set `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in your local `.env`, and then run `php artisan migrate`. Set credentials appropriate to your own database locally; never commit `.env`.

### Optional frontend assets

The JobTrack pages use inline CSS and do not load Vite assets, so these commands are not required to run `/applications`. The default `/` welcome page does load Vite assets. To build those assets, install Node.js and npm, then run:

```powershell
npm install
npm run build
```

## Running tests

The PHPUnit configuration sets `DB_CONNECTION=sqlite` and `DB_DATABASE=:memory:` for tests, so database-backed tests use a fresh in-memory database rather than the development database. Ensure `pdo_sqlite` is enabled for the PHP CLI, then run:

```powershell
php artisan test
```

## Project structure

- `app/Http/Controllers/` - request handling, validation, queries, and redirects.
- `app/Models/` - Eloquent models, including `JobApplication`.
- `database/migrations/` - application and Laravel database table definitions.
- `resources/views/job_applications/` - Blade list, create, and edit pages.
- `routes/web.php` - web routes for the application workflow.
- `tests/Feature/` and `tests/Unit/` - feature behavior and unit tests.
- `config/`, `public/`, and `resources/` - Laravel configuration, web entry point, and frontend assets.

## Current limitations

JobTrack currently has no authentication and does not assign applications to individual users. Anyone who can access the application can see and change the same application records. It is intended as a local, single-user prototype, not a production-ready multi-user service.

## Setup notes to verify

- Replace the repository URL placeholder above after creating a Git remote for JobTrack. The current configured remotes point to the Laravel starter repository, not a JobTrack portfolio repository.
- These fresh-machine instructions have not been run on a clean installation. Verify the PHP SQLite extension and migrations on the target machine. Verify the optional asset build if you plan to use the default `/` welcome page or modify frontend assets.
