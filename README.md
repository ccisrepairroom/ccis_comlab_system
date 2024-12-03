<b>Run the ff. command</b>
After installing XAMPP
Go to php.ini. Search "zip" and remove its semicolon. Search again "intl". Then last search "gd" and remove its semicolon.

cp .env.example .env <br>
composer update<br>
npm install <br>
npm run build <br>
php artisan migrate <br>
php artisan migrate:fresh --seed <br>
php artisan key:generate <br>
php artisan storage:link <br>
npm run dev <br>
php artisan shield:install <br>
php artisan shield:super-admin <br>
--What panel? ***type "admin" then enter
--fresh install? ***type "yes"
php artisan serve <br>

Go to file, "UserSeeder", and log in that credential for Superadmin.
When get to the dashboard, go to roles, select a role, and hit the "select all" beside guard web

Change code for the policies per role. Go to "C:\Users\vgpu-client-2\Desktop\ccis_comlab_system\app\Policies"
Then change the code for each file...The codes to copy and paste can be located here: "C:\Users\Desktop\ccis_comlab_system\1caps\Policies to copy paste"
Just change the codes every file.

To improve performance, run the ff. commands
php artisan optimize:clear <br>
php artisan icons:cache <br>

