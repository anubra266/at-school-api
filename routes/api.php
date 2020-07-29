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

    Route::post('update_profile', 'UserController@update_profile');
    Route::post('update_password', 'UserController@update_password');
    Route::post('update_profile_image', 'UserController@update_profile_image');
    Route::get('user', 'UserController@authed');
    Route::get('user/{userId}/detail', 'UserController@show');
    Route::get('homeinfo', 'UserController@info');

    Route::get('organization', 'OrganizationController@index');
    Route::post('organization', 'OrganizationController@store');

    Route::get('environ', 'EnvironController@index');
    Route::post('environ', 'EnvironController@store');

    Route::post('checkclassroom', 'ClassroomController@checkclassroom_return');
    Route::get('classroom', 'ClassroomController@index');
    Route::post('classroom', 'ClassroomController@store');
    Route::get('joinedclassrooms', 'ClassroomController@studindex');
    Route::post('joinclassroom', 'ClassroomController@join');
    Route::post('classroomrole', 'ClassroomController@role');
    Route::post('classroomcheck', 'ClassroomController@check');
    Route::post('classroommembers', 'ClassroomController@members');

    Route::get('gettestresults/{test}', 'TheoryTestController@getresults');
    Route::get('theorytestsubmissions/{test}', 'TheoryTestController@submissions');
    Route::post('marktestdetails/{test}', 'TheoryTestController@markdetails');
    Route::post('theorytests', 'TheoryTestController@index');
    Route::post('theorysolutions', 'TheoryTestController@solutions');
    Route::post('alltheorytests', 'TheoryTestController@mark');
    Route::post('alltheorytestsresults', 'TheoryTestController@results');

    Route::get('theorytest/{test}', 'TheoryTestController@show');
    Route::get('theorytestdetails/{test}', 'TheoryTestController@showdetails');
    Route::post('storetheorytest', 'TheoryTestController@store');
    Route::post('addtheorytestsolution/{test}', 'TheoryTestController@addsolution');
    Route::post('updatetheorytestsolution/{solution}', 'TheoryTestController@updatesolution');

    Route::post('theoryquestion/{test}', 'TheoryQuestionController@store');
    Route::post('updatetheoryquestion/{question}', 'TheoryQuestionController@edit');
    Route::post('submittheoryquestion/{question}', 'TheoryQuestionController@submit');
    Route::post('finishmarktest/{test}', 'TheoryQuestionController@finishmark');
    Route::put('updatetheoryanswer/{answer}', 'TheoryQuestionController@resubmit');

    Route::post('objectivetests', 'ObjectiveTestController@index');
    Route::post('objectivesolutions', 'ObjectiveTestController@solutions');

    Route::get('gettestoresults/{test}', 'ObjectiveTestController@getresults');
    Route::post('objectivetest', 'ObjectiveTestController@store');
    Route::get('showobjectivetest/{test}', 'ObjectiveTestController@show');
    Route::get('objectivetestreview/{test}', 'ObjectiveTestController@showreview');
    Route::get('objectivetestresult/{test}', 'ObjectiveTestController@showresult');
    Route::post('submitobjectivetest/{test}', 'ObjectiveTestController@submit');
    Route::post('allobjectivetestsresults', 'ObjectiveTestController@results');
    Route::post('allobjectivetests', 'ObjectiveTestController@mark');

    Route::post('addtoobjectivetest/{test}/question', 'ObjectiveQuestionController@store');
    Route::post('editobjectivetest/{question}/question', 'ObjectiveQuestionController@edit');
    Route::post('importobjectivetest/{test}/excel', 'ObjectiveQuestionController@storeexcel');
    Route::post('addobjectivetestsolution/{question}', 'ObjectiveQuestionController@addsolution');
    Route::post('updateobjectivetestsolution/{solution}', 'ObjectiveQuestionController@updatesolution');


});

