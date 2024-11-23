<?php

use App\Livewire\HomePage;
use App\Livewire\Signup;
use Illuminate\Support\Facades\Route;



Route::get('/',HomePage::class)->name('home');
Route::get('/signup', Signup::class)->name('signup');
