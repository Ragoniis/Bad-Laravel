<?php
namespace Controllers;
require_once "DB.php";
require_once "Request.php";
require_once "Models/Author.php";
require_once "JsonResponse.php";
use Models\Author;
use Request;
use JsonResponse;

class AuthorController{

    static public function create(Request $request){
      $author = Author::create($request);
      $headers = ["Accept" => "application/json"];
      response($author, 201, $headers)->send();  
    }
    
    static public function index(Request $request){
        $authors = Author::all();
        $headers = ["Accept" => "application/json"];
        response($authors, 200, $headers)->send();
    }

    static public function show(Request $request){
        $author = Author::find($request->aid);
        $headers = ["Accept" => "application/json"];
        response($author, 200, $headers)->send();
    }

    static public function update(Request $request){
        $author = Author::update($request);
        $headers = ["Accept" => "application/json"];
        response($author, 200, $headers)->send();
    }

    static public function delete(Request $request){
        $authorDeleted = Author::delete($request);
        $headers = ["Accept" => "application/json"];
        response($authorDeleted, 200, $headers)->send();
    }
} 