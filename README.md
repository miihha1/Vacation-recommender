# Vacation Recommender

A Laravel web application that recommends vacation destinations based on travel dates, trip length, vacation style, preferred temperature, and flight distance from Vienna.

The application ranks destinations by how well they match the selected preferences and provides useful context for each recommendation, including climate data, current weather, country information, currency exchange rates, and the nearest airport.

## Features

- Search destinations by month or custom date range.
- Filter by trip length, vacation type, preferred temperature, and flight distance.
- Rank results by preference match.
- Show an explanation for each recommended destination.
- Display destination details, including:
  - average monthly temperature,
  - minimum and maximum temperatures,
  - current weather forecast when available,
  - nearest airport,
  - country flag, country name, and capital city,
  - currency and exchange rate against EUR,
  - generated travel recommendation.
- Compare two destinations side by side.
- Track application statistics:
  - total and unique visits in the last 60 minutes,
  - traffic by time of day,
  - searchable destination statistics table,
  - visitor preference charts.

The application does not store raw IP addresses. Unique visits are calculated using an HMAC hash with the Laravel `APP_KEY`.

## Tech Stack

- PHP
- Laravel
- MySQL or MariaDB
- Blade
- CSS
- JavaScript
- Chart.js
- Lucide icons

## External APIs

- GeoNames flags: `https://www.geonames.org/flags/x/{iso}.gif`
  - Displays country flags by two-letter ISO code.
- Frankfurter API: `https://api.frankfurter.app/latest`
  - Provides current exchange rates against EUR.
- Open-Meteo Forecast API: `https://api.open-meteo.com/v1/forecast`
  - Provides current weather forecasts for destination coordinates.

## Database

The application uses the following main tables:

- `destinations` - destination data, vacation types, currency, location, and flight distance.
- `climates` - average monthly minimum and maximum temperatures.
- `countries` - country names, ISO codes, and capital cities.
- `airports` - airport data used to find the nearest airport.
- `visits` - visit records without storing raw IP addresses.
- `search_logs` - search result logs used for statistics.

The database schema is available in the Laravel migration:

```bash
database/migrations/2026_05_04_000001_create_vacation_tables.php
```

An SQL dump is also included:

```bash
database/dump.sql
```

## Installation

Clone the repository and install PHP dependencies:

```bash
composer install
```

Create the environment file and generate the application key:

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database connection in `.env`:

```env
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vacation_recommender
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seed the database:

```bash
php artisan migrate:fresh --seed
```

Alternatively, import the included SQL dump:

```bash
mysql -u root -p vacation_recommender < database/dump.sql
```

Install frontend dependencies and build assets:

```bash
npm install
npm run build
```

Start the local development server:

```bash
php artisan serve
```

## Server Requirements

- PHP with the required Laravel extensions
- MySQL or MariaDB
- Composer
- Node.js and npm

Commonly required PHP extensions include:

- `pdo_mysql`
- `mbstring`
- `dom`
- `xml`
- `xmlwriter`
- `curl`
- `zip`
