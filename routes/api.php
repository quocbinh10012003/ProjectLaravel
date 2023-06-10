<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController as APIBlogController;
use App\Http\Controllers\API\BookController as APIBookController;
use App\Http\Controllers\API\CategoryController as APICategoryController;
use App\Http\Controllers\API\CommentController as APICommentController;
use App\Http\Controllers\API\FoodController as APIFoodController;
use App\Http\Controllers\API\MessageContrller as APIMessageContrller;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\RattingCotroller as APIRattingCotroller;
use App\Http\Controllers\API\ReplyController as APIReplyController;
use App\Http\Controllers\API\UserController as APIUserController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('user/message', [APIMessageContrller::class, 'message']);
Route::get('user/message/{id}', [APIMessageContrller::class, 'index']);

Route::get('categories', [APICategoryController::class, 'index']);
Route::get('index', [APIFoodController::class, 'index']);
Route::get('detail/{id}', [APIFoodController::class, 'edit']);
Route::get('getTop12FoodsHot', [APIFoodController::class, 'getTop12FoodsHot']);
Route::post('food/store', [OrderController::class, 'store']);
Route::post('food/update', [OrderController::class, 'update']);
Route::post('all-foods/classify', [APIFoodController::class, 'classify']);
Route::get('checkout/{id}', [OrderController::class, 'index']);
Route::post('order/{id}', [OrderController::class, 'assert']);
Route::post('book/add', [APIBookController::class, 'store']);
Route::post('ratting', [APIRattingCotroller::class, 'store']);
Route::post('ratting-user', [APIRattingCotroller::class, 'show']);
Route::post('all-ratting', [APIRattingCotroller::class, 'index']);
Route::post('all-comment', [APICommentController::class, 'index']);
Route::post('comment', [APICommentController::class, 'store']);
Route::post('comment/reply', [APIReplyController::class, 'store']);
Route::get('comment/reply/count', [APIReplyController::class, 'index']);
Route::post('profile/{id}', [APIUserController::class, 'show']);

Route::middleware(['auth:sanctum', 'isAPIAdmin'])->group(function () {
    Route::get('/checkingAuthenticated', function () {
        return response()->json(['status' => 200,'r'=>"u admin"], 200);
    });
    Route::post('/admin/chat/{id}', [APIMessageContrller::class, 'index']);
    Route::post('/admin/message', [APIMessageContrller::class, 'getMessageUser']);
    Route::get('/admin/category', [APICategoryController::class, 'index']);
    Route::post('/admin/add-category', [APICategoryController::class, 'store']);
    Route::delete('/admin/delete-category/{id}', [APICategoryController::class, 'destroy']);
    Route::post('/admin/category-search', [APICategoryController::class, 'search']);
    Route::get('/admin/category/{id}/edit', [APICategoryController::class, 'edit']);
    Route::post('/admin/category/{id}/update', [APICategoryController::class, 'update']);
    //Food
    Route::post('/admin/add-food', [APIFoodController::class, 'store']);
    Route::get('/admin/foods', [APIFoodController::class, 'index']);
    Route::post('/admin/delete-food/{id}', [APIFoodController::class, 'destroy']);
    Route::get('/admin/foods/{id}/edit', [APIFoodController::class, 'edit']);
    //Blogs
    Route::get('blogs', [APIBlogController::class, 'index']);
    Route::post('/admin/add-blog', [APIBlogController::class, 'store']);
    //Book
    Route::post('/admin/updatebook/{id}', [APIBookController::class, 'update']);
    Route::delete('/admin/book/delete/{id}', [APIBookController::class, 'destroy']);
    Route::get('/admin/all-book', [APIBookController::class, 'index']);
    
Route::post('logout', [AuthController::class, 'logout']);

});
// Route::middleware(['auth:sanctum', 'isAPIUser'])->group(function () {
//     Route::get('/checkingAuthenticated', function () {
//         return response()->json(['status' => 200,'r'=>"u user"], 200);
//     });

// Route::post('logout', [AuthController::class, 'logout']);
   
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
