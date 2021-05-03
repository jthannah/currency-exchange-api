###Instructions to run locally

1. run `composer install`
2. Make sure 'pdo_sqlite' extension is enabled in PHP.ini
3. Copy the contents of '.env.example' file into a '.env' file 
4. Change the value of 'DB_DATABASE' in your new .env file to be the absolute path to /database/database.sqlite
5. run `php artisan migrate`
6. run `php artisan serve`
7. The API should now be running at http://127.0.0.1:8000
