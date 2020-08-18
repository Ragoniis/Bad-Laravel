<?php
namespace Controllers;
require_once "DB.php";
require_once "Request.php";
require_once "Models/User.php";
require_once "Models/Author.php";
require_once "Auth.php";
require_once "JsonResponse.php";
use Models\Author;
use Auth;
use Request;
use JsonResponse;

class AuthorController{

    public static function getAllAuthors(Request $request){
       $authors = Author::all();
       $headers = ["Accept" => "application/json"];
       response($authors, 200, $headers)->send();
    }
    public static function getAuthor(Request $request){
      $author = Author::find($request->aid);
      $livros = $author->livros();
      $headers = ["Accept" => "application/json"];
      response([$author, $livros], 200, $headers)->send();
    }
    public static function updateAuthor(Request $request){
        $author = Author::update($request);
        $headers = ["Accept" => "application/json"];
        response($author, 200, $headers)->send();
    }

    public static function deleteAuthor(Request $request)
    {
        $deleted = Author::delete($request);
        $headers = ["Accept" => "application/json"];
        response($deleted, 200, $headers)->send();
    }

    public static function createAuthor(Request $request)
    {
        $author = Author::create($request);
        $headers = ["Accept" => "application/json"];
        response($author, 201, $headers)->send();
    }

}
