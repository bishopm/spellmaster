<?php

Route::middleware(['handlecors'])->group(function () {
    Route::get('/api/welcome/{id}', ['uses'=>'Bishopm\Spellmaster\Http\Controllers\Api\UsersController@welcome','as'=>'api.words.welcome']);
    Route::get('/api/words', ['uses'=>'Bishopm\Spellmaster\Http\Controllers\Api\WordsController@index','as'=>'api.words.index']);
    Route::get('/api/words/{search}', ['uses'=>'Bishopm\Spellmaster\Http\Controllers\Api\WordsController@search','as'=>'api.words.search']);
    Route::get('/api/word', ['uses'=>'Bishopm\Spellmaster\Http\Controllers\Api\WordsController@random','as'=>'api.words.random']);
    Route::post('/api/word', ['uses'=>'Bishopm\Spellmaster\Http\Controllers\Api\WordsController@store','as'=>'api.words.store']);
    Route::post('/api/answer', ['uses'=>'Bishopm\Spellmaster\Http\Controllers\Api\WordsController@answer','as'=>'api.words.answer']);
    Route::get('/api/users', ['uses'=>'Bishopm\Spellmaster\Http\Controllers\Api\UsersController@index','as'=>'api.users.index']);
});