<?php


use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
})->name('home');*/

Route::get('/', [\App\Http\Controllers\PostController::class,'index'])->name('home');
Route::get('/article/{slug}', [\App\Http\Controllers\PostController::class,'show'])->name('posts.single');
Route::get('/category/{slug}', [\App\Http\Controllers\CategoryController::class, 'show'])->name('categories.single');
Route::get('/tag/{slug}', [\App\Http\Controllers\TagController::class, 'show'])->name('tags.single');
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');



// 'namespace' => 'Admin'
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [MainController::class,'index'])->name('index');
    Route::resource('/categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('/tags', \App\Http\Controllers\Admin\TagController::class);
    Route::resource('/posts', \App\Http\Controllers\Admin\PostController::class);
});
// ->middleware('admin')

Route::middleware('guest')->group(function () {
    Route::get('/register', [UserController::class,'create'])->name('register.create');
    Route::post('/register', [UserController::class,'store'])->name('register.store');

    Route::get('/login', [UserController::class,'loginForm'])->name('login.create');
    Route::post('/login', [UserController::class,'login'])->name('login');
});

Route::get('/logout', 'UserController@logout')->name('logout')->middleware('auth');
