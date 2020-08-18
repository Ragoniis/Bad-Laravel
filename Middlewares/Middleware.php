<?php
namespace Middleware;
require_once "Request.php";
require_once "Handler.php";
interface Middleware{

    static public function handle(\Request $request,\Handler $next);
}
