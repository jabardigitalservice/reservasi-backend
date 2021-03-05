#!/bin/sh
printf "Checking database connection...\n\n"
mysql_ready() {
    mysqladmin ping --host=$DB_HOST --user=$DB_USERNAME --password=$DB_PASSWORD > /dev/null 2>&1
}

while !(mysql_ready)
do
    sleep 3
    echo "Waiting for database connection ..."
done

php artisan config:clear
php artisan cache:clear
php artisan route:clear
#php artisan migrate --no-interaction -vvv

echo "done!"