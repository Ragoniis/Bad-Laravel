<?php
namespace Models;

require_once "DB.php";

class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $password;

    public function __construct()
    {
        //unset($this->password);
    }

    public static function all():array
    {
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select `id`,`name`,`email` from user");
        $stm->execute();
        $users = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return $users;
    }

    public static function find($id):User
    {
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select * from user where id=?");
        $stm->setFetchMode(\PDO::FETCH_CLASS, 'Models\User');
        $stm->execute([$id]);
        $user = $stm->fetch();
        $stm->closeCursor();
        unset($user->password);
        return $user;
    }

    public static function update(\Request $request):User
    {
        $pdo = \DB::connect();
        $query = "UPDATE user SET " ;
        $arr = [];
        $parameters = [];
        if ($request->name) {
            array_push($parameters, '`name`=? ');
            array_push($arr, $request->name);
        }
        if ($request->email) {
            array_push($parameters, '`email`=? ');
            array_push($arr, $request->email);
        }
        $query .= implode(',', $parameters) . 'where `id`=?';
        array_push($arr, $request->id);
        $stm = $pdo->prepare($query);
        $stm->execute($arr);
        return self::find($request->id);
    }

    public static function delete(\Request $request):int
    {
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Delete from user where id=?");
        $stm->execute([$request->id]);
        //$stm->closeCursor();
        return $stm->rowCount();
    }

    public static function create(\Request $request):User
    {
        $pdo = \DB::connect();
        $stm = $pdo->prepare("INSERT INTO user (`name`,`email`,`password`) VALUES (?,?,?)");
        $stm->execute([$request->name,$request->email,password_hash($request->password, PASSWORD_DEFAULT)]);
        $stm->closeCursor();
        return self::find($pdo->lastInsertId());
    }
}
