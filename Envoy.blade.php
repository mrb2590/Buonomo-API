@include('vendor/autoload.php')

@servers(['prod' => '127.0.0.1'])

@task('deploy', ['on' => 'prod', 'confirm' => true])
    cd /var/www/sites/buonomo-api

    @if ($branch)
        git pull origin {{ $branch }}
    @endif

    php artisan down

    php artisan view:clear

    php artisan config:clear

    php artisan route:clear

    git pull origin master --force

    php artisan migrate --force

    composer install --optimize-autoloader --no-dev

    php artisan config:cache

    php artisan route:cache

    php artisan queue:restart

    php artisan up
@endtask