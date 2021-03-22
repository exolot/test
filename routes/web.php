<?php

use Illuminate\Support\Facades\Route;
use App\Models\{User, Company};
use Illuminate\Http\JsonResponse;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/populate', function () {
    $json = file_get_contents(storage_path('challenge.json'));
    $objs = json_decode($json, 1);

    foreach ($objs['users'] as $user) {
        $dbUser = User::create([
            'user_id' => $user['id'],
            'name' => $user['name'],
            'age' => $user['age']
        ]);
        $dbUser->companies()->createMany(array_map(function ($item) {
            return [
                "company_id" => $item['id'],
                "name" => $item['name'],
                "started_at" => $item['started_at']
            ];
        }, $user['companies']));
    }
    return 'population done';
});


Route::get('/companies/{min?}/{max?}', function ($min = null, $max = null) {

    $clause = Company::with('user');

    if ($min && $max) {
        return new JsonResponse($clause->whereHas('user', function ($query) use ($min, $max) {
            return $query->where('age', '>', $min)->where('age', '<', $max);
        })->get());
    }

    return new JsonResponse($clause->get());

});


