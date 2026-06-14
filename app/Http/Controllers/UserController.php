<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Helpers\JwtAuth;

class UserController extends Controller
{

    public function register(Request $request)
    {
        $email = $request->input('email');
        $name = $request->input('name');
        $surname = $request->input('surname');
        $role = 'ROLE_USER';
        $password = $request->input('password');

        if(!is_null($email) && !is_null($password) && !is_null($name)){
            
            $user = new User();
            $user->email = $email;
            $user->name = $name;
            $user->surname = $surname;
            $user->role = $role;

            $pwd = hash('sha256', $password);
            $user->password = $pwd;

            $isset_user = User::where('email', $email)->first();

            if(!isset($isset_user)){
                
                $user->save();

                $data = [
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Usuario creado correctamente.'
                ];

            } else {
                $data = [
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Usuario duplicado, no puede registrarse.'
                ];
            }
        } else {
            $data = [
                'status' => 'error',
                'code' => 400,
                'message' => 'Usuario no creado.'
            ];
        }

        return response()->json($data, 200);
    }

    public function login(Request $request){

        $jwtAuth = new JwtAuth();

        //Recibir POST
        $email = $request->input('email');
        $password = $request->input('password');
        $getToken = $request->input('gettoken');

        //Cifrar la password
        $pwd = hash('sha256', $password);
        
        if(!is_null($email) && !is_null($password) && !$getToken){
           
            $signup = $jwtAuth->signup($email, $pwd);
            
        }elseif($getToken != null){

            $signup = $jwtAuth->signup($email, $pwd, $getToken);

        }else{

            $signup = array(
                'status' => 'error',
                'message' => 'Envia tus datos por post'
            );
        }

        if (is_array($signup) && isset($signup['status']) && $signup['status'] == 'error') {
            return response()->json($signup, 401);
        }

        return response()->json($signup, 200);
    }
}
