@echo off
echo "Role & User Seeder"
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=DummyusersSeeder