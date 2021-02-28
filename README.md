## Requirements
Minimum PHP v8.0.0 or higher

## Installation
1. Clone this repository
```bash
git clone https://github.com/najdovski/arthaus
```
2. Change directory to arthaus
```bash
cd arthaus
```
3. Install composer
```bash
composer install
```
4. Install and run npm
```bash
npm install && npm run dev
```
5. Copy and rename .env.example to .env and edit the environment variables
```bash
cp .env.example .env
```
6. Generate new app key
```bash
php artisan key:generate
```
7. Run database migration
```bash
php artisan migrate
```
8. [Optional] Run database users and activities seeders
```bash
php artisan db:seed --class=UsersTableSeeder && php artisan db:seed --class=ActivitiesTableSeeder
```

9. Run the Laravel's inbuilt webserver
```bash
php artisan serve
```