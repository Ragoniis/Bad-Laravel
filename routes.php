<?php
require_once("Router/Route.php");
use Router\Route;

Route::get("/user","Controllers\UserController@index",[]);
Route::get("/showuser","Controllers\UserController@show",[]);
Route::post("/upuser","Controllers\UserController@update",[]);
Route::post("/cuser","Controllers\UserController@create",[]);
Route::post("/duser","Controllers\UserController@delete",[]);

Route::get("/author", "Controllers\AuthorController@index", ["IsPalmeira"]);
Route::get("/showAuthor", "Controllers\AuthorController@show",[]);
Route::post("/cauthor", "Controllers\AuthorController@create",[]);
Route::post("/upauthor", "Controllers\AuthorController@update",[]);
Route::post("/delauthor", "Controllers\AuthorController@delete",[]);

