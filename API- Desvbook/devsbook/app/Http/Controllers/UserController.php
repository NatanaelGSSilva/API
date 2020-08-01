<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\user;

class UserController extends Controller
{
     private $loggedUser;
     public function __construct(){
        $this->middleware('auth:api');// tem que estar logado para usar esse carinha

        $this->loggedUser = auth()->user();// Pegar asinformações do meu usuario
    }

    public function update(Request $request){

        $name = $request->input('name');
        $email = $request->input('email');
        $birthdate = $request->input('birthdate');
        $city = $request->input('city');
        $work = $request->input('work');
        $password = $request->input('password');
        $password_confirm = $request->input('password_confirm');

        $user = User::find($this->loggedUser['id']);// pegar o usuario logado

        if($email){
            if($email != $user->email){
                $emailExists  = User::where('email', $email)->count();
                if($emailExists === 0){
                    $user->email = $email;
                }else{
                    return reponse()->json(['message'=> 'E-mail ja existende na DB'],401);
                }

            }
        }

        if($password && $password_confirm){
            if($password === $password_confirm){
                $hash = password_hash($password,PASSWORD_DEFAULT);
                $user->password = $hash;
            }else{
                return response()->json(['error'=> 'As senhas não batem']);
            }

        }

        $user->save();
        return response()->json(['message'=>'usuario alterado com sussesso'],200);
    }


}
