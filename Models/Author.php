<?php
namespace Models;

require_once "DB.php";
require_once "Request.php";
use Models\Author;
use Request;

class Author
{
    public int $aid;
    public string $name;
    public string $surname;

    public function livros()
    {
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select * from livros where aid=? ");
        $stm->execute([$this->aid]);
        $livros = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return $livros;
    }
    public static function all()
    {
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select * from authors");
        $stm->execute();
        $authors = $stm->fetchAll(\PDO::FETCH_ASSOC);
        $response = [];
        foreach ($authors as $author) {
            $author= self::find($author['aid']);
            array_push($response, $author);
            array_push($response, $author->livros());
        }
        return $response;
    }
    public static function find($aid)
    {
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select * from authors where aid=?");
        $stm->setFetchMode(\PDO::FETCH_CLASS, 'Models\Author');
        $stm->execute([$aid]);
        $author = $stm->fetch();
        $stm->closeCursor();
        return $author;
    }
    public static function update(\Request $request)
    {
        $pdo = \DB::connect();
        $query = "UPDATE authors SET " ;
        $arr = [];
        $parameters = [];
        if ($request->name) {
            array_push($parameters, '`name`=? ');
            array_push($arr, $request->name);
        }
        if ($request->surname) {
            array_push($parameters, '`surname`=? ');
            array_push($arr, $request->surname);
        }
        $query .= implode(',', $parameters) . 'where `aid`=?';
        array_push($arr, $request->aid);
        $stm = $pdo->prepare($query);
        $stm->execute($arr);
        return self::find($request->aid);
    }

    public static function delete(\Request $request)
    {
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Delete from authors where aid=?");
        $stm->execute([$request->aid]);
        //$stm->closeCursor();
        return;
    }
    public static function create(\Request $request)
    {
        $pdo = \DB::connect();
        $stm = $pdo->prepare("INSERT INTO authors (`name`,`surname`) VALUES (?,?)");
        $stm->execute([$request->name,$request->surname]);
        $stm->closeCursor();
        return self::find($pdo->lastInsertId());
    }
}
