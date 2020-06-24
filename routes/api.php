<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');

Route::middleware('auth:api')->group(function() {

    Route::get('user', 'UserController@authed');
    Route::get('user/{userId}/detail', 'UserController@show');

    Route::get('organization', 'OrganizationController@index');
    Route::post('organization', 'OrganizationController@store');

    Route::get('environ', 'EnvironController@index');
    Route::post('environ', 'EnvironController@store');

    Route::get('classroom', 'ClassroomController@index');
    Route::post('classroom', 'ClassroomController@store');
    Route::get('joinedclassrooms', 'ClassroomController@studindex');
    Route::post('joinclassroom', 'ClassroomController@join');
    Route::post('classroomrole', 'ClassroomController@role');
    Route::post('classroomcheck', 'ClassroomController@check');

    Route::post('theorytests', 'TheoryTestController@index');
    
    Route::post('storetheorytest', 'TheoryTestController@store');
    Route::get('theorytest/{test}', 'TheoryTestController@show');

    Route::post('theoryquestion/{test}', 'TheoryQuestionController@store');
    Route::post('submittheoryquestion/{question}', 'TheoryQuestionController@submit');
 
    Route::post('objectivetests', 'ObjectiveTestController@index');

    Route::post('objectivetest', 'ObjectiveTestController@store');
    Route::get('showobjectivetest/{test}', 'ObjectiveTestController@show');
    Route::get('objectivetestreview/{test}', 'ObjectiveTestController@showreview');
    Route::get('objectivetestresult/{test}', 'ObjectiveTestController@showresult');
    Route::post('submitobjectivetest/{test}', 'ObjectiveTestController@submit');

    Route::post('addtoobjectivetest/{test}/question', 'ObjectiveQuestionController@store');
    Route::post('importobjectivetest/{test}/excel', 'ObjectiveQuestionController@storeexcel');


});

