<?php
    use Illuminate\Support\Facades\Route;

    Route::namespace('App\Http\Controllers\Admin')-> group (function(){

        include(base_path('routes/api/admin_user_auth_routes.php'));
        include(base_path('routes/api/migration_routes.php'));

        Route::group(['middleware' => ['auth:admin_api']], function () {
            include(base_path('routes/api/admin_routes.php'));
            include(base_path('routes/api/settings_routes.php'));
            include(base_path('routes/api/dispensary/subscription_plan_routes.php'));
            include(base_path('routes/api/dispensary/sms_purchase_routes.php'));
            include(base_path('routes/api/faqs_routes.php'));
            include(base_path('routes/api/pages_routes.php'));
            include(base_path('routes/api/dispensary/dispensary_routes.php'));
            include(base_path('routes/api/customer/customer_routes.php'));
            include(base_path('routes/api/email_template_routes.php'));
        });
    });
