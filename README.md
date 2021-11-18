### Solution Gist:
Developed a Customer Import Module using Lumen and Laravel Doctrine.
You can view the whole module at ```app/Modules/Customer```

### Installation
This project uses the latest or 8.x of [Lumen](https://lumen.laravel.com/docs/8.x).

1. Run the composer installation:
    ```sh
    composer install
   ```

2. Create a copy `.env.example` and make your changes.

3. Provision the database:
    ```sh
    php artisan doctrine:schema:create
   ```

4. Serve the application by using [Laravel Homestead](http://laravel.com/docs/homestead),
[Laravel Valet](http://laravel.com/docs/valet) or the built-in PHP development server:
    ```sh
    php -S localhost:8000 -t public
   ```

### Import
Run the import command via:

```sh
php artisan customer:import --count=[How many users to import, default: 100]
```

### Unit Test
Simple, run:

```sh
vendor/bin/phpunit
```
