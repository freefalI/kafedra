<?php

use App\Models\Employee;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('employees', EmployeeController::class);
    $router->resource('activities', ActivityController::class);
    $router->resource('leaves', LeaveController::class);
    $router->get('leaves-calendar', 'LeaveController@calendar');
    $router->get('widgets', 'WidgetController@index');

    $router->resource('degrees', ScienceDegreeController::class);
    $router->resource('titles', AcademicTitleController::class);
    $router->resource('positions', PositionController::class);

});

Route::group([
    'prefix'        => config('admin.route.prefix') . '/api',
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('employees',  function (Request $request) {
        $q = $request->get('q');

        return  Employee::where('name', 'like', "%$q%")
            ->orWhere('surname', 'like', "%$q%")
            ->paginate(null, ['id', 'name', 'surname', 'parent_name'])
            ->through(function ($user, $key) {
                $user['text'] = $user->full_name;
                return $user;
            });
    });

    $router->get('users',  function (Request $request) {
        $q = $request->get('q');

        return  Administrator::where('username','!=','admin')
        ->where('name', 'like', "%$q%")
            ->orWhere('username', 'like', "%$q%")
            ->paginate(null, ['id', 'name', 'username'])
            ->through(function ($user, $key) {
                $user['text'] = $user->username;
                return $user;
            });
    });
});
