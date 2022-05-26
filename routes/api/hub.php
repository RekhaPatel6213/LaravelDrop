<?php
    use Illuminate\Support\Facades\Route;
    //use App\Http\Middleware\DropHeaders;

    Route::namespace('App\Http\Controllers\Admin')-> group (function(){
        include base_path('routes/api/dispensary/dispensary_user_auth_routes.php');

        Route::group(['middleware' => ['auth:'.config('app.admin_guard').','.config('app.dispensary_guard'), 'drop_headers']], function () {
            include base_path('routes/api/hub/dispensary_routes.php');
            include base_path('routes/api/hub/customer_routes.php');
        });

    });

    Route::namespace('App\Http\Controllers\Driver')-> group (function(){
        Route::group(['middleware' => ['auth:'.config('app.admin_guard').','.config('app.dispensary_guard'), 'drop_headers']], function () {
            include base_path('routes/api/hub/driver_routes.php');
        });
    });

    Route::namespace('App\Http\Controllers\Territory')-> group (function(){
        Route::group(['middleware' => ['auth:'.config('app.admin_guard').','.config('app.dispensary_guard'), 'drop_headers']], function () {
            include base_path('routes/api/hub/territory_routes.php');
			});
    });

    Route::namespace('App\Http\Controllers\Hub')-> group (function(){
        Route::group(['middleware' => ['auth:'.config('app.admin_guard').','.config('app.dispensary_guard'), 'drop_headers']], function () {
            foreach (File::allFiles(__DIR__ . '/hub') as $route_file) {
                if(!in_array($route_file->getFilename(), ['dispensary_routes.php','customer_routes.php','driver_routes.php','territory_routes']))
                    require_once $route_file->getPathname();
            }
        });
    });
