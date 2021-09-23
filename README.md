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
'AppCrudController' => Hcode\Project\Http\Controllers\AppCrudController::class,
'AppController' => Hcode\Project\Http\Controllers\AppController::class,
'AppFunctions' => Hcode\Project\Http\Controllers\hcFunctions::class,
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