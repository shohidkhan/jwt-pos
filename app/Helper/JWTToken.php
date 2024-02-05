<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken{
    public static function createToken($userEmail,$id):string{
        $key=env("JWT_KEY");
        $payload=[
            "iss"=>"laravel-token",
            "iat"=>time(),
            "exp"=>time()+60*60*24,
            "email"=>$userEmail,
            "id"=>$id
        ];
        $encode = JWT::encode($payload,$key,"HS256");
        return $encode;
    }
    public static function createTokenForResetPassowrd($userEmail):string{
        $key=env("JWT_KEY");
        $payload=[
            "iss"=>"laravel-token",
            "iat"=>time(),
            "exp"=>time()+60*60*24,
            "userEmail"=>$userEmail,
            "id"=>"0"
        ];
        $encode = JWT::encode($payload,$key,"HS256");
        return $encode;
    }

    public static function verifyToken($token):string|object{
        try{
            if($token===null){
                return "Unauthorized";
            }else{
                $key=env("JWT_KEY");
                $decode = JWT::decode($token,new Key($key,"HS256"));
                return $decode;
            }
        }catch(Exception $exception){
            return "Unauthorized";
        }
    }
}