<?php
namespace Controllers;
require_once "DB.php";
require_once "Request.php";
require_once "Models/User.php";
require_once "JsonResponse.php";
require_once "Auth.php";
use Auth;
use Models\User;
use Request;
use JsonResponse;

class AuthController{
    public static function register(Request $request){
      $user = User::create($request);
      $headers = ["Accept" => "application/json"];
      $token = Auth::generateToken($user->id);
      response([$user, $token], 201, $headers)->send();

    }
    public static function login(Request $request){
        if(Auth::attempt(["email" => $request->email, "password" => $request->password])){
            $pdo = \DB::connect();
            $stm = $pdo->prepare("Select * from user where email=?");
            $stm->setFetchMode(\PDO::FETCH_CLASS, 'Models\User');
            $stm->execute([$request->email]);
            $user = $stm->fetch();
            $token = Auth::generateToken($user->id);
            $headers = ["Accept" => "application/json"];
            response($token, 200, $headers)->send();
        }
        else{
            ob_get_clean();
            http_response_code(500);
            echo("Senha e/ou Email não existente ou errados");
            throw new \Exception("Senha e/ou Email não existente ou errados");
            die();
        }
    }
    public static function getDetails(Request $request){
      $user = User::find($request->user_id);
      $headers = ["Accept" => "application/json"];
      response($user, 200, $headers)->send();
    }


}
