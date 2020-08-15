<?php
namespace Controllers\API;

require_once "DB.php";
require_once "Request.php";
require_once "Models/User.php";
require_once "Models/Auth.php";
require_once "JsonResponse.php";

use Models\User;
use Models\Auth;
use Request;
use JsonResponse;

class PassportController {

  static public function registerUser(Request $request) {
    $headers = ["Accept" => "application/json"];
    $errors = self::validate($request);
    $hasValidationErrors = count($errors) !== 0 ? true : false;

    if (!$hasValidationErrors) {
      $request->password = password_hash($request->password, PASSWORD_BCRYPT);

      $user = User::create($request);

      $user->token = Auth::createToken($user);

      response($user, 200, $headers)->send();
    } else {
      response($errors, 206, $headers)->send();
    }
  }

  static public function loginUser(Request $request) {
    if (Auth::attempt($request->email, $request->password)) {
      $user = Auth::user();
      $headers = ["Accept" => "application/json"];
      response($user, 200, $headers)->send();
    }
  }

  static public function logout() {
    $headers = ["Accept" => "application/json"];
    try {
      $token = Auth::userToken();

      $pdo = \DB::connect();
      $stm = $pdo->prepare("UPDATE oauth_access_tokens SET revoked=? WHERE jwt=?");
      $stm->execute([true, $token]);
      $stm->closeCursor();
  
      response('Successfully logged out', 200, $headers)->send();
    } catch(\Exception $error) {
      response('Unauthorized', 401, $headers)->send();
    }
  }

  static public function getDetails() {
    $headers = ["Accept" => "application/json"];
    try {
      $user=Auth::user();
      response($user, 200, $headers)->send();
    } catch(\Exception $error) {
      response('Unauthorized', 401, $headers)->send();
    }
  }

  static public function validate(Request $request) {
    $errors = [];

    if(!isset($request->name)) {
      array_push($errors, 'Name is required');
    }

    if(!isset($request->email)) {
      array_push($errors, 'Email is required');
    }

    if(!isset($request->password)) {
      array_push($errors, 'Password is required');
    }

    return $errors;
  }

}