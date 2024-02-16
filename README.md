Step 1. Create a database on mysql with the name tutorial_db;
Step 2. Grant permissions to tutorial_user to the mysql Database;
Step 3. Run:
    cp .env.example .env
    php artisan key:generate
Step 4. Properly install and enable pdo_mysql
Step 5. On the laravel directory run:
    php artisan migrate:install
Step 6. When it is installed run:
    php artisan migrate
