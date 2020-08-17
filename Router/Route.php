<?php
namespace Router;
require_once 'Controllers/UserController.php';
require_once 'Controllers/AuthorController.php';
require_once 'Request.php';
require_once 'Middlewares/IsPalmeira.php';
require_once 'Middlewares/PassaTudo.php';
require_once 'Handler.php';
require_once "Middlewares/CORS.php";

use Controllers\UserController;
use Controllers\AuthorController;

class Route{
    private static $get_routes = [];
    private static $post_routes = [];
    private static $middlewares = ["CORS"];//, "PassaTudo", "IsPalmeira"];

    static public function get(string $url,string $controllerMethod, array $mwares){
        self::$get_routes[$url] = ["controllerMethod" => $controllerMethod, "middlewares" => $mwares];
    }
    
    static public function post(string $url,string $controllerMethod, array $mwares){
        self::$post_routes[$url] = ["controllerMethod" => $controllerMethod, "middlewares" => $mwares];
    }

    static public function handle(){
        $url = $_SERVER["REQUEST_URI"];
        $path = parse_url($url, PHP_URL_PATH);
        switch($_SERVER["REQUEST_METHOD"]){
            case "GET":
                if(!isset(self::$get_routes[$path]["controllerMethod"])){
                    http_response_code(404);
                    echo 'NOT FOUND';
                    die();    
                }
                $function = explode("@",self::$get_routes[$path]["controllerMethod"]);
                $request = new \Request($_GET);
                $routeMiddleware = self::$get_routes[$path]["middlewares"];
                foreach (self::$middlewares as $middleware) {
                    array_push($routeMiddleware, $middleware);
                }
                
                $handler = new \Handler($routeMiddleware,$function);
                $handler($request);
                break;
            case "POST":
                if(!isset(self::$post_routes[$path])){
                    http_response_code(404);
                    echo 'NOT FOUND';
                    die();    
                }
                $function = explode("@",self::$post_routes[$path]);
                $request = new \Request($_POST);
                $handler = new \Handler(self::$middlewares,$function);
                $handler($request);
                break;
            case "OPTIONS":
                $request = new \Request($_POST);
                $handler = new \Handler(self::$middlewares,function(){});
                $handler($request);
                http_response_code(204);
                break;
            default: 
                http_response_code(405);
                throw new \Exception("Method Not Suported");
                die();
        }
    }
};
