<?php
namespace Models;
require_once "DB.php";

class Author{
    public $aid;
    public $name;
    public $surname;

    static public function create(\Request $request):Author{
      $pdo = \DB::connect();
      $stm = $pdo->prepare("INSERT INTO authors (`name`,`surname`) VALUES (?,?)");
      $stm->execute([$request->name,$request->surname]);
      $stm->closeCursor();
      return self::find($pdo->lastInsertId());
    }

    static public function all():array{
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select * from authors");
        $stm->execute();
        $authors = $stm->fetchAll(\PDO::FETCH_ASSOC);
        // var_dump($authors);
        return $authors;
    }

    static public function find($aid):Author{
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select * from authors where aid=?");
        $stm->setFetchMode(\PDO::FETCH_CLASS, 'Models\Author');
        $stm->execute([$aid]);
        $author = $stm->fetch();
        $stm->closeCursor();
        return $author;
    }

    static public function update(\Request $request):Author{
        $pdo = \DB::connect();
        $query = "UPDATE authors SET " ;
        $arr = [];
        $parameters = [];
        if($request->name){
            array_push($parameters,'`name`=? ');
            array_push($arr,$request->name);
        }
        if($request->surname){
            array_push($parameters,'`surname`=? ');
            array_push($arr,$request->surname);
        }
        $query .= implode(',',$parameters) . 'where `aid`=?';
        array_push($arr,$request->aid);
        $stm = $pdo->prepare($query);
        $stm->execute($arr);
        return self::find($request->aid);
    }

    static public function delete(\Request $request):int{
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Delete from authors where aid=?");
        $stm->execute([$request->aid]);
        //$stm->closeCursor();
        return $stm->rowCount();
    }

}