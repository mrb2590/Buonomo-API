@include('vendor/autoload.php')

@servers(['prod' => '127.0.0.1'])

@task('deploy', ['on' => 'prod', 'confirm' => true])
    cd /var/www/sites/buonomo-api

    php artisan down

    php artisan view:clear

    php artisan config:clear

    php artisan route:clear

    @if ($branch)
        git pull origin {{ $branch }}
    @else
        git pull origin master --force
    @endif

    php artisan migrate --force

    composer install --optimize-autoloader --no-dev

    php artisan config:cache

    php artisan route:cache

    php artisan horizon:terminate

    php artisan up
@endtask