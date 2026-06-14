<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class JwtAuth {

    public string $key;
     
    public function __construct()
    {
        $this->key= "CLavePrivadafsdfadfdsfds435435435-43543-543-43-54-54543543545436756ookyud";
    }

    public function signup($email, $password, $getToken = null){
        
        $user = User::where([
                    'email' => $email,
                    'password' => $password
                ])->first();

        $signup = false;

        if(is_object($user)){
            
            $signup = true;
    
        }
        
        if($signup){
            
            //Generar Token
            $token = [
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'surname' => $user->surname,
                'iat' => time(),
                'exp' => time() + (7 * 24 * 60 * 60)
            ];

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));

            if(is_null($getToken)){
                
                return $jwt;
            }else{

                return $decoded;
            }
        
        } else {

            //Devolver un error
            return [
                'status' => 'error',
                'message' => 'Login ha fallado'
            ];
        }
    }

    public function checkToken($jwt, $getIdentity = null){

        $auth = false;
        $decoded = null;
        
        try {
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));

        } catch (\Exception $e) {
            $auth = false;
        }

        if(is_object($decoded) && isset($decoded->sub)){

            $auth = true;
        } else {
            $auth = false;
        }

        if($getIdentity){
            return $decoded;
        }

        return $auth;
    }

    
}