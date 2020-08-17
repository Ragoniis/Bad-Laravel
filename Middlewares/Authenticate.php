<?php
namespace Middleware;
require_once "Middlewares/Middleware.php";

class Authenticate implements Middleware {

    public function handle(\Request $request,\Handler $next){
        if(isset($request->token) && $request->token != ""){
            $next($request);
        }else {
            ob_get_clean();
            http_response_code(401);
            echo("Token is null");
            throw new \Exception("Token is null");
            die();
        }
    }
}