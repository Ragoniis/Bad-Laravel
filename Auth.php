<?php

class Auth
{
    public static function generateToken($user_id)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        // Create token payload as a JSON string
        $date = new DateTime("NOW");
        $date->add(new DateInterval('P10D'));
        $payload = json_encode(['user_id' => $user_id, 'validate' => date_format($date, "Y-m-d")]);
        // Encode Header to Base64Url String
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        // Encode Payload to Base64Url String
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        // Create Signature Hash
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);
        // Encode Signature to Base64Url String
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        return $jwt;
    }

    public static function attempt($array)
    {
        $pdo = \DB::connect();
        $stm = $pdo->prepare("Select * from user where email=?");
        $stm->setFetchMode(\PDO::FETCH_CLASS, 'Models\User');
        $stm->execute([$array["email"]]);
        $user = $stm->fetch();
        $password = $user->password;
        if(empty($user) || !password_verify($array['password'], $password))
          return false;
        return true;
    }
}
