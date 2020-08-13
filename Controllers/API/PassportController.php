<?php
namespace Controllers\API;

require_once "DB.php";
require_once "Request.php";
require_once "Models/User.php";
require_once "JsonResponse.php";

use Models\User;
use Request;
use JsonResponse;

class PassportController {

  static public function registerUser(Request $request) {

    $headers = ["Accept" => "application/json"];
    $errors = PassportController::validate($request);

    if (count($errors) === 0) {
      $request->password = password_hash($request->password, PASSWORD_BCRYPT);

      $user = User::create($request);

      $user->token = PassportController::createToken($user);

      response($user, 200, $headers)->send();
    } 
    response($errors, 206, $headers)->send();

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

  static public function createToken($user) {
    // Create token header and payload as a JSON string
    $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
    $payload = json_encode(['sub' => $user->id, 'name' => $user->name]);

    // Encode header and payload to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    // Create hash and encode to Base64Url Signature
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'OLJGHFnvjh1254ckdinAokiU415!', true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    User::storeToken($user->id, $jwt);

    return $jwt;
  }

}