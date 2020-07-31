<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api',[
            'except'=>[
                'login',
                'create',
                'unauthorized']]);// tem que estar logado para usar esse carinha
    }

    public function unauthorized(){
        return response()->json(['error'=>'email ou senha invalidos']);
    }

    public function login(Request $request){
         $credentials = request(['email', 'password']);

         if (! $token = auth()->attempt($credentials)) {
            return $this->unauthorized();
        }

        return $this->respondWithToken($token);
    }

    public function logout(){
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(){
        return $this->respondWithToken(auth()->refresh());
    }

     protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function create(Request $request){


        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $birthdate = $request->input('birthdate');

        if($name && $email && $password && $birthdate){

            $emailExists = User::where('email', $email)->count();
            if($emailExists === 0){

                $hash = password_hash($password, PASSWORD_DEFAULT);

                $newUser = new User();
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->password = $hash;
                $newUser->birthdate = $birthdate;
                $newUser->save();
            }else{
                return response()->json(['message' =>'Email ja cadastrado', 401]);
            }
                $token= auth()->attempt([
                    'email'=> $email,
                    'password'=> $password
                ]);

                return response()->json(['message'=>'usuario cadastrado com sucesso','token'=> $token], 201);
    }
}

}
