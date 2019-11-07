# Libra

[![Build Status](https://travis-ci.com/nikolaynizruhin/libra.svg?branch=master)](https://travis-ci.com/nikolaynizruhin/libra)
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
5. Migrate database (--seed optional):
```
php artisan migrate --seed
```
6. Generate app key:
```
php artisan key:generate
```

## Testing

```
vendor/bin/phpunit
```
