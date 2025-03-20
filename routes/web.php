<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('home.homepage');
});



Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/home', function () {
    return view('user.home');
});