<?php
require_once("Router/Route.php");
use Router\Route;

Route::get("/users","Controllers\UserController@index");
Route::get("/showuser","Controllers\UserController@show");
Route::post("/upuser","Controllers\UserController@update");
Route::post("/cuser","Controllers\UserController@create");
Route::post("/duser","Controllers\UserController@delete");

Route::get("/authors","Controllers\AuthorController@index");
Route::get("/showauthor","Controllers\AuthorController@show");
Route::post("/createauthor","Controllers\AuthorController@create");
Route::post("/updateauthor","Controllers\AuthorController@update");
Route::post("/deleteauthor","Controllers\AuthorController@delete");

Route::post("/login","Controllers\API\PassportController@loginUser");
Route::post("/register","Controllers\API\PassportController@registerUser");
Route::get("/logout","Controllers\API\PassportController@logout");
Route::post("/getDetails","Controllers\API\PassportController@getDetails");