<?php
namespace Controllers;

require_once "DB.php";
require_once "Request.php";
require_once "Models/User.php";
require_once "JsonResponse.php";
use Models\User;
use Request;
use JsonResponse;

class UserController
{
    public static function index(Request $request)
    {
        $users = User::all();
        $headers = ["Accept" => "application/json"];
        response($users, 200, $headers)->send();
    }

    public static function show(Request $request)
    {
        //echo 'sadasd';
        $user = User::find($request->id);
        //header("Content-type: application/json");
        $headers = ["Accept" => "application/json"];
        response($user, 200, $headers)->send();
    }

    public static function update(Request $request)
    {
        //echo 'sadasd';
        $user = User::update($request);
        //header("Content-type: application/json");
        $headers = ["Accept" => "application/json"];
        response($user, 200, $headers)->send();
    }

    public static function delete(Request $request)
    {
        echo $request->name;
        $deleted = User::delete($request);
        //header("Content-type: application/json");
        $headers = ["Accept" => "application/json"];
        response($deleted, 200, $headers)->send();
    }

    public static function create(Request $request)
    {
        $user = User::create($request);
        //header("Content-type: application/json");
        $headers = ["Accept" => "application/json"];
        response($user, 201, $headers)->send();
    }
}
