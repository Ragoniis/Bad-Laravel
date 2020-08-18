<?php
namespace Router;
require_once 'Controllers/UserController.php';
require_once 'Controllers/AuthorController.php';
require_once 'Request.php';
require_once 'Middlewares/IsPalmeira.php';
require_once 'Handler.php';
require_once "Middlewares/CORS.php";
require_once "Middlewares/Authentication.php";
require_once "Controllers/AuthController.php";

use Controllers\UserController;
use Controllers\AuthorController;

class Route{
    private static $get_routes = [];
    private static $post_routes = [];
    private static $middlewares = ["CORS"];//,"IsPalmeira"];

    //Apena será necessário passar no headers o token com a flag Bearer
    //que irá acontecer a autenticação
    static public function get(string $url,string $controllerMethod, $auth=true){
        self::$get_routes[$url] = $controllerMethod;
        if(isset($_SERVER["HTTP_AUTHORIZATION"]))
          self::$middlewares[1] = "Authentication";
    }

    static public function post(string $url,string $controllerMethod, $auth=false){
        self::$post_routes[$url] = $controllerMethod;
        if(isset($_SERVER["HTTP_AUTHORIZATION"]))
          self::$middlewares[1] = "Authentication";
    }

    static public function handle(){
        $url = $_SERVER["REQUEST_URI"];
        $path = parse_url($url, PHP_URL_PATH);
        switch($_SERVER["REQUEST_METHOD"]){
            case "GET":
                if(!isset(self::$get_routes[$path])){
                    http_response_code(404);
                    echo 'NOT FOUND';
                    die();
                }
                $function = explode("@",self::$get_routes[$path]);
                $request = new \Request($_GET);
                if(isset(self::$middlewares[1])){
                  $token = preg_split('/\s+/', $_SERVER["HTTP_AUTHORIZATION"]);
                  $request->token = $token[1];
                }
                $handler = new \Handler(self::$middlewares,$function);
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
                if(isset(self::$middlewares[1])){
                  $token = preg_split('/\s+/', $_SERVER["HTTP_AUTHORIZATION"]);
                  $request->token = $token[1];
                }
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
