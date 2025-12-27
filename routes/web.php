<?php

use Illuminate\Support\Facades\Route;

// This is the only route needed in web.php if your React app handles everything
// and all API calls go to routes/api.php.
Route::get('/', function () {
    return "This is to test the push on git repository";
});

// REMOVED: require __DIR__.'/auth.php'; 
// This line registered the conflicting, CSRF-protected /login route.