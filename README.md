***
# Hcode PROYECT
### Support
| Laravel Version | Package Version |
| --------------- |---------------- |
| 7.* | 1.* |
## Preparing

###### Prerequisites 
1. require install ui/auth
```
composer require laravel/ui
```  
```
php artisan ui:auth
```

### Installation
2. Install package
```
composer hcode/proyect
```

3. copy in config/app.php
```
Hcode\Project\Providers\ProjectServiceProvider::class,
```

```
'HcAppCrud' => Hcode\Project\Http\Controllers\AppCrudController::class,
// 'HcApp' => Hcode\Project\Http\Controllers\AppController::class,
// 'HcAppFunction' => Hcode\Project\Http\Controllers\AppFunctionController::class,
```

4. vendor::publish
```
php artisan vendor:publish --provider="Hcode\Project\Providers\ProjectServiceProvider"
```

## Initial Crud
#### Command
copy all directories crud
```
php artisan make:hcodeCrud
```

###### not required, only to personalizate view crud
```
php artisan make:hcodeViews
```

## Initial Vue
#### Command
copy all directories components vue
```
php artisan make:hcodeVue
```
 
## Middleware "hcAccess" to permission with routes
#### copy in config/app.php
```
Hcode\Project\Providers\ProjectAppAccessServiceProvider::class,
```

## Middleware "hcMenu" build menu for application
#### copy in config/app.php
```
Hcode\Project\Providers\ProjectAppMenuServiceProvider::class,
```

## Middleware "tenant" build menu for application
#### copy in config/app.php
```
Hcode\Project\Providers\ProjectAppTenantServiceProvider::class,
```
#### add config/database.php
```
'tenants' => [
    'driver'         => 'mysql',
    'url'            => env('DATABASE_URL_TENANTS'),
    'host'           => env('TENANTS_HOST', '127.0.0.1'),
    'port'           => env('TENANTS_PORT', '3306'),
    'database'       => env('TENANTS_DATABASE', 'forge'),
    'username'       => env('TENANTS_USERNAME', 'forge'),
    'password'       => env('TENANTS_PASSWORD', ''),
    'unix_socket'    => env('TENANTS_SOCKET', ''),
    'charset'        => 'utf8mb4',
    'collation'      => 'utf8mb4_unicode_ci',
    'prefix'         => '',
    'prefix_indexes' => true,
    'strict'         => true,
    'engine'         => null,
    'options'        => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
    ]) : [],
],
```
#### use in .env
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

TENANTS_CONNECTION=tenants
TENANTS_HOST=mysql
TENANTS_PORT=3306
TENANTS_DATABASE=app_tenants
TENANTS_USERNAME=root
TENANTS_PASSWORD="password"
```

***
## Template AdminTL3
#### Command copy all directories
```
php artisan make:hcodeTemplate adminLTE 
```


## Helps
====================================================================
#### Route.php example
```
Auth::routes();
Route::get('/', function () {
    return view('home.index');
});

Route::middleware(['auth', 'hcAccess', 'hcMenu'])->group(function () {

    Route::get('/', 'Backend\InicioController@index')->name('inicio.index');
    Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function() {
        Route::resource('roles', 'RolesController');
        Route::resource('usuarios', 'UsuariosController');
    });
});
```