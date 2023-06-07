<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/users', function (Request $request) {
    return User::paginate(10);
});
Route::post('/users', function (Request $request) {
    $validatedData = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => ['required', 'email', Rule::unique('users')],
    ]);
    User::create($validatedData);
});
Route::put('/users/{user}', function (Request $request, User $user) {
    $validatedData = $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
    ]);
    $user->update($validatedData);
});
Route::delete('/users/{user}', function (Request $request, User $user) {
    $user->delete();
});
