<b>Run the ff. command</b>

composer update
npm install
npm run build
cp .env.example .env
php artisan migrate
php artisan key:generate
rm public/storage
php artisan storage:link
php artisan serve
npm run dev
php artisan shield:install
php artisan make:filament-user
php artisan optimize:clear
php artisan icons:cache
