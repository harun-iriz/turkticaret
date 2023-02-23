<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\User;
use App\Traits\ResponseAPI;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    use ResponseAPI;

    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(),[
                    'email' => 'required|email|string',
                    'password' =>'required',
                ]
            );

            if($validator->fails())
                return $this->error(message: $validator->errors(), statusCode:422);

            if(Auth::guard('web')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){

                $user = Auth::user();
                $user['token'] = $user->createToken('MyApp')->plainTextToken;

                return $this->success(message: "User successfully login", data:$user);

            }
            else{
                $error = ['errors'=>"Email or password incorrect"];
                return $this->error(message: $error, statusCode:401);
            }
        }catch (\Exception $e){
            return $this->error(message: $e->getMessage(), statusCode: $e->getCode());
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            if (Auth::check()) {
                $accessToken = $request->bearerToken();
                $token = PersonalAccessToken::findToken($accessToken);
                $token->delete();
                $result = [
                    'logout'=>'success'
                ];
                return $this->success(message: "User successfully logout", data: $result);
            }
            else{
                return $this->error(message: 'Unauthorized', statusCode:401);
            }
        }catch (\Exception $e){
            return $this->error(message: $e->getMessage(), statusCode: $e->getCode());
        }
    }
}
