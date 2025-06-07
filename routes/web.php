<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Models\Book;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    return redirect()->route('books.index');
});
    //Book::truncate();
    //\App\Models\Book::factory(100000)->create();
  // return \App\Models\Book::all();
//     \Illuminate\Support\Facades\Cache::set('books', $books,50);
  //  return \Illuminate\Support\Facades\Cache::get('books');
//});

Route::resource('books', BookController::class)
->only('show','index');

Route::resource('books.reviews', ReviewController::class)
    ->scoped(['review'=>'book'])
    ->only('create','store');
