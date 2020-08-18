<?php
namespace Middleware;
require_once "Middlewares/Middleware.php";
require_once "DB.php";

class Authentication implements Middleware{
    static public function handle(\Request $request,\Handler $next){
          if(self::checkValidation($request))
              $next($request);
          else{
            ob_get_clean();
            http_response_code(401);
            echo("Unauthorized");
            throw new \Exception("Unauthorized");
            die();
          }
    }

    static public function checkValidation(\Request $request){
        $token = explode(".", $request->token);
        $payload = base64_decode ($token[1]);
        $fakeSignature = hash_hmac('sha256', $token[0] . "." . $token[1], 'abC123!', true);
        $date = date('Y-m-d');
        $payload = json_decode($payload);
        $base64UrlFakeSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($fakeSignature));
        $validDate = date_create($payload->validate);
        $date = new \DateTime("NOW");
        $date = date_create($date->format("Y-m-d"));
        if($token[2] == $base64UrlFakeSignature && $date <= $validDate){
          $request->user_id = $payload->user_id;
          return true;
        }
        return false;
    }

}
