<?php
namespace Middleware;
require_once "Middlewares/Middleware.php";

class PassaTudo implements Middleware{
    public function handle(\Request $request,\Handler $next){
        $next($request);
    }
}