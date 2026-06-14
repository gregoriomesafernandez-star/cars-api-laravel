<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Models\Car;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
     public function index(){
         
         $cars = Car::all()->load('user');

         return response()->json([
            'cars' => $cars,
            'status' => 'success'
         ], 200);
     }

     public function show(int $id){
         
         $car = Car::find($id);

         if(is_object($car)){

            $car = Car::find($id)->load('user');

            return response()->json(array(
               'car' => $car,
               'status' => 'success'
            ), 200);

         }else{

            return response()->json(array(
               'message' => 'El coche no existe',
               'status' => 'error'
            ), 404);

         }
     }

     public function store(Request $request){
         $hash = $request->header('Authorization', null);

         $jwtAuth = new JwtAuth();
         $checkToken = $jwtAuth->checkToken($hash);

         if ($checkToken) {

            // Recoger datos
            $params_array = $request->all();

            // Validación
            $validator = Validator::make($params_array, [
                  'title'       => 'required',
                  'description' => 'required',
                  'price'       => 'required',
                  'status'      => 'required'
            ]);

            if ($validator->fails()) {
                  return response()->json([
                     'message' => 'Error de validación',
                     'errors'  => $validator->errors(),
                     'status'  => 'error',
                     'code'    => 400
                  ], 400);
            }

            // Conseguir usuario identificado
            $user = $jwtAuth->checkToken($hash, true);

            // Guardar coche
            $car = new Car();
            $car->user_id = $user->sub;
            $car->title = $request->input('title');
            $car->description = $request->input('description');
            $car->price = $request->input('price');
            $car->status = $request->input('status');

            $car->save();

            $data = [
                  'status' => 'success',
                  'code'   => 200,
                  'car'    => $car
            ];

         } else {

            $data = [
                  'message' => 'Token inválido o expirado',
                  'status'  => 'error',
                  'code'    => 401
            ];

         }

         return response()->json($data, $data['code']);
     }

     public function update(int $id, Request $request){
      
      $hash = $request->header('Authorization', null);

      $jwtAuth = new JwtAuth();
      $checkToken = $jwtAuth->checkToken($hash);

      if ($checkToken) {

         // Recoger datos
         $params_array = $request->all();

         // Validación
         $validator = Validator::make($params_array, [
               'title'       => 'required|min:5',
               'description' => 'required',
               'price'       => 'required',
               'status'      => 'required'
         ]);

         if ($validator->fails()) {
               return response()->json([
                  'message' => 'Error de validación',
                  'errors'  => $validator->errors(),
                  'status'  => 'error',
                  'code'    => 400
               ], 400);
         }

         $params_array = array_intersect_key($params_array, array_flip([
               'title',
               'description',
               'price',
               'status'
         ]));

         // Actualizar coche
         Car::where('id', $id)->update($params_array);

         // Recuperar coche actualizado
         $car = Car::find($id);

         return response()->json([
               'car'    => $car,
               'status' => 'success'
         ], 200);

      }

      return response()->json([
         'message' => 'Usuario no autenticado',
         'status'  => 'error',
         'code'    => 401
      ], 401);
    }

     public function destroy(int $id, Request $request){
         
         $hash = $request->header('Authorization', null);
         $jwtAuth = new JwtAuth();
         $checkToken = $jwtAuth->checkToken($hash);

         if ($checkToken) {

            $car = Car::find($id);
            
            if (!$car) {
               return response()->json([
                  'message' => 'Coche no encontrado',
                  'status'  => 'error'
               ], 404);
            }
            
            $car->delete();

            return response()->json([
               'car' => $car,
               'status' => 'success'
            ], 200);

         } else {

            // Devolver ERROR
            $data = [
               'message' => 'Login incorrecto',
               'status'  => 'error',
               'code'    => 400
            ];
         }

         return response()->json($data, 200);
     }
}
