<?php

namespace Laravel\Breeze\Console;

use Illuminate\Filesystem\Filesystem;

trait InstallsModuleApiStack
{
    /**
     * Install the API module-auth stack.
     *
     * @return int|null
     */
    protected function installModuleApiStack()
    {
        $this->runCommands(['php artisan install:module-api']);

        $files = new Filesystem;

        // Controllers...
        $files->ensureDirectoryExists(base_path('Modules/Auth/app/Http/Controllers/Auth'));
        $files->copyDirectory(__DIR__.'/../../stubs/module-api/app/Http/Controllers/Auth', base_path('Modules/Auth/app/Http/Controllers/Auth'));

        // Middleware...
        $files->ensureDirectoryExists(base_path('Modules/Auth/app/Http/Middleware'));
        $files->copyDirectory(__DIR__.'/../../stubs/module-api/app/Http/Middleware', base_path('Modules/Auth/app/Http/Middleware'));

        $this->installMiddlewareAliases([
            'verified' => '\Modules\Auth\app\Http\Middleware\EnsureEmailIsVerified::class',
        ]);

        $this->installMiddleware([
            '\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class',
        ], 'api', 'prepend');

        // Requests...
        $files->ensureDirectoryExists(base_path('Modules/Auth/app/Http/Requests/Auth'));
        $files->copyDirectory(__DIR__.'/../../stubs/module-api/app/Http/Requests/Auth', base_path('Modules/Auth/app/Http/Requests/Auth'));

        // Providers...
        $files->ensureDirectoryExists(base_path('Modules/Auth/app/Providers'));
        $files->copyDirectory(__DIR__.'/../../stubs/module-api/app/Providers', base_path('Modules/Auth/app/Providers'));

        // Routes...
        copy(__DIR__.'/../../stubs/module-api/routes/api.php', base_path('Modules/Auth/routes/api.php'));
        copy(__DIR__.'/../../stubs/module-api/routes/web.php', base_path('Modules/Auth/routes/web.php'));
        copy(__DIR__.'/../../stubs/module-api/routes/auth.php', base_path('Modules/Auth/routes/auth.php'));

        // Configuration...
        $files->copyDirectory(__DIR__.'/../../stubs/module-api/config', base_path('Modules/Auth/config'));

        // Environment...
        if (! $files->exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
        }

        file_put_contents(
            base_path('.env'),
            preg_replace('/APP_URL=(.*)/', 'APP_URL=http://localhost:8000'.PHP_EOL.'FRONTEND_URL=http://localhost:3000', file_get_contents(base_path('.env')))
        );

        // Tests...
        if (! $this->installTests()) {
            return 1;
        }

        $files->delete(base_path('tests/Feature/Auth/PasswordConfirmationTest.php'));

        // Cleaning...
        $this->removeScaffoldingUnnecessaryForModuleApis();

        $this->components->info('Auth Module scaffolding installed successfully.');
    }

    /**
     * Remove any application scaffolding that isn't needed for APIs.
     *
     * @return void
     */
    protected function removeScaffoldingUnnecessaryForModuleApis(): void
    {
        $files = new Filesystem;

        // Remove frontend related files...
        $files->delete(base_path('Modules/Auth/package.json'));
        $files->delete(base_path('Modules/Auth/vite.config.js'));

        // Remove user model
        $files->delete(base_path('app/Models/User.php'));

        // Remove users migrations
        $files->delete(base_path('database/migrations/0001_01_01_000000_create_users_table.php'));

        // Remove users factory
        $files->delete(base_path('database/factories/UserFactory.php'));

        // Remove users seeders
        $files->delete(base_path('database/seeders/DatabaseSeeder.php'));

        // Remove Laravel "welcome" view...
        $files->delete(base_path('Modules/Auth/resources/views/welcome.blade.php'));
        $files->put(base_path('Modules/Auth/resources/views/.gitkeep'), PHP_EOL);

        // Remove CSS and JavaScript directories...
        $files->deleteDirectory(base_path('Modules/Auth/resources/assets/sass'));
        $files->deleteDirectory(base_path('Modules/Auth/resources/assets/js'));
    }
}
