<?php
namespace Models;

session_start();

require_once "DB.php";
require_once "Models/User.php";
require_once "Request.php";

use Models\User;
use Request;

class Auth { 

  static public function storeToken($id, $jwt) {
    $pdo = \DB::connect();
    $stm = $pdo->prepare("INSERT INTO oauth_access_tokens (`jwt`,`user_id`, `revoked`) VALUES (?,?,?)");
    $stm->execute([$jwt,$id, false]);
    $stm->closeCursor();

    $_SESSION['token'] = $jwt;

    return User::find($id);
  }

  static public function attempt($email, $password) {
    $user = User::where($email);

    if($user) {
      if (password_verify($password, $user['password'])) {
        $user = self::prepareJSONParameter($user);
        $user->token = self::createToken($user);
        return true;
      } else {
        response('Incorrect password', 401, $headers)->send();
      }
    } else {
      response('Email not registered', 401, $headers)->send();
    }
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

    self::storeToken($user->id, $jwt);

    return $jwt;
  }

  static public function userToken() {
    $token = self::verifySession();
    session_destroy();
    return $token;
  }

  static public function user() {
    $token = self::verifySession();
    $pdo = \DB::connect();
    $stm = $pdo->prepare("SELECT user_id FROM oauth_access_tokens WHERE jwt=?");
    $stm->execute([$token]);
    $userId = $stm->fetch();
    return User::find($userId['user_id']);
  }

  static public function verifySession() {
    if(isset($_SESSION['token'])) {
      $token = $_SESSION['token'];
      return $token;
    }
    throw new \Exception("No active session");
  }

  static public function prepareJSONParameter($user) {
    // remove to return a JSON without password
    unset($user['password']);
    unset($user['0'], $user['1'], $user['2'], $user['3']);

    // function createToken requires a JSON as parameter
    $user = new Request($user);

    return $user;
  }
  
}