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
        $stm = $pdo->prepare("Select a.*, b.title, b.pages, b.ISBN FROM authors as A LEFT JOIN books as B on a.aid = b.aid");
        $stm->execute();
        $authorsWithBooks = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return $authorsWithBooks;
    }

    static public function find($aid) {
        $pdo = \DB::connect();

        $stmt = $pdo->prepare("Select count(a.aid) as total_books FROM authors as A LEFT JOIN books as B on a.aid = b.aid where a.aid=?");
        $stmt->execute([$aid]);
        $totalBooksOfAuthor = $stmt->fetch();

        $stm = $pdo->prepare("Select a.*, b.title, b.pages, b.ISBN FROM authors as A LEFT JOIN books as B on a.aid = b.aid where a.aid=?");
        $stm->setFetchMode(\PDO::FETCH_CLASS, 'Models\Author');
        $stm->execute([$aid]);

        if( (int) $totalBooksOfAuthor['total_books'] !== 1) {
            $authorWithBooks = $stm->fetchAll(\PDO::FETCH_ASSOC);
            return $authorWithBooks;
        }

        $authorWithBook = $stm->fetch();
        $stm->closeCursor();
        return $authorWithBook;
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