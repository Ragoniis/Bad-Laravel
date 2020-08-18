<?php
require_once("Router/Route.php");
use Router\Route;

Route::get("/user","Controllers\UserController@index");
Route::get("/showuser","Controllers\UserController@show");
Route::post("/upuser","Controllers\UserController@update");
Route::post("/cuser","Controllers\UserController@create");
Route::post("/duser","Controllers\UserController@delete");
//rotas da CRUD de autores
Route::get("/getAllAuthors","Controllers\AuthorController@getAllAuthors");
Route::get("/getAuthor","Controllers\AuthorController@getAuthor");
Route::post("/updateAuthor","Controllers\AuthorController@updateAuthor");
Route::post("/createAuthor","Controllers\AuthorController@createAuthor");
Route::post("/deleteAuthor","Controllers\AuthorController@deleteAuthor");
//rotas de autenticação, necessário apenas passar o token no header com o Bearer
Route::get("/getDetails","Controllers\AuthController@getDetails");
Route::post("/login","Controllers\AuthController@login");
Route::post("/register","Controllers\AuthController@register");
