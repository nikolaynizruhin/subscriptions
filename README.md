# Libra

[![GitHub Actions](https://github.com/nikolaynizruhin/libra/workflows/Laravel/badge.svg)](https://github.com/nikolaynizruhin/libra/workflows/Laravel/badge.svg)
[![StyleCI](https://github.styleci.io/repos/220341513/shield?branch=master)](https://github.styleci.io/repos/220341513)

## Getting started

### Installation

1. Clone this repository:
```
git clone https://github.com/nikolaynizruhin/libra.git
```
2. Copy .env file and update it with your variables:
```
cp .env.example .env
```
3. Install composer dependencies:
```
composer install
```
4. Install and compile npm dependencies:
```
yarn && yarn dev
```
5. Migrate database:
```
php artisan migrate
```
6. Generate app key:
```
php artisan key:generate
```

## Testing
PHPUnit:
```
vendor/bin/phpunit
```
Dusk:
```
php artisan dusk
```
