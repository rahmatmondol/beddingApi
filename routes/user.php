<?php
use Illuminate\Support\Facades\Route;

Route::get('service/details/{id}', function(){
    return view('pages.service.details');
});