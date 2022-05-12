<?php
//Route::post('qiwipayment', 'Chronos\App\Components\AppComponent@returnPaymentStatus');
//Route::get('makeusers', 'Chronos\App\Components\AppComponent@makeUsers');
Route::get('check/payments', 'Chronos\App\Components\CommerceComponent@checkApiPayments');

//Route::get('test', 'Chronos\App\Components\AppComponent@onGetChildrenPayments');
Route::get('test', 'Chronos\App\Components\CommerceComponent@test');
Route::get('api/exp_users', 'Chronos\App\Components\AppComponent@expUsers');

Route::get('product/get_uri/{id}', 'Chronos\App\Components\AppComponent@getUriProduct');

/* Titamik Test route */
Route::get('api/check', 'Chronos\App\Components\AppComponent@checkGoogleApi');

//Route::get('queue', 'Chronos\App\Components\AppComponent@queueTest');

Route::get('user_to_csv/{period}', 'Chronos\App\Components\AppComponent@userToCsv');

Route::get('backend/redirect_to_google', function(){
    return Redirect::to('https://docs.google.com/spreadsheets/d/1gSevii9phqLzrA9B2i3cnBWnNy6upEo4lJg7406JC90/edit?usp=sharing');
});