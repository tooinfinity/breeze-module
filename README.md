# breeze-module

to install this package run
```
composer require tooinfinity/breeze-module:dev-main --dev
```

and this package require laravel module package to install it run 

```
composer require nwidart/laravel-modules
```

after that run this commands

```
php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
```
By default the module classes are not loaded automatically. You can autoload your modules using psr-4. For example :

```
"extra": {
    "laravel": {
        "dont-discover": []
    },
    "merge-plugin": {
        "include": [
            "Modules/*/composer.json"
        ]
    }
},
```

Tip: don't forget to run composer dump-autoload afterwards

```
composer dump-autoload
```

```
php artisan module:make Auth
```


to create auth API run this

```
php artisan breeze:install
```
and choose from the following 

```
'blade' => 'Blade with Alpine',
'livewire' => 'Livewire (Volt Class API) with Alpine',
'livewire-functional' => 'Livewire (Volt Functional API) with Alpine',
'react' => 'React with Inertia',
'vue' => 'Vue with Inertia',
'api' => 'API only',
'module-api' => 'laravel modules API only',
'module-blade' => 'laravel modules Blade with Alpine',
```
