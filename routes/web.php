<?php

use App\Livewire\Contact;
use App\Livewire\HomePage;
use App\Livewire\Login;
use App\Livewire\Profile;
use App\Livewire\Signup;
use App\Livewire\Tutorial;
use Illuminate\Support\Facades\Route;


Route::get('/', HomePage::class)->name('home');
Route::get('/signup', Signup::class)->name('signup');
Route::get('/login', Login::class)->name('login');
Route::get('/profile', Profile::class)->name('profile');
Route::get('/tutorials', Tutorial::class)->name('tutorials');
Route::get('/contact', Contact::class)->name('contact');
