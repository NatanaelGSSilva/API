<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\user;
use Image;

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

        if($name){
            $user->name = $name;
        }

        if($birthdate){
           $user->birthdate = $birthdate;
            }



        if($city){
            $user->city = $city;
        }

        if($work){
            $user->work = $work;
        }

        if($email){
            if($email != $user->email){
                $emailExists  = User::where('email', $email)->count();
                if($emailExists === 0){
                    $user->email = $email;
                }else{
                    return response()->json(['message'=> 'E-mail ja existende na DB'],401);
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

    public function updateAvatar(Request $request){
        $array = ['error'=>''];
        $typesImage = ['image/jpg', 'image/jpeg', 'image/png'];

        $image = $request->file('avatar');

        if($image){
            if(in_array($image->getClientMimeType(), $typesImage)){
                $filename = md5(time().rand(0,9999)).'.jpg';

                $destPath = public_path('/media/avatar');

                $img = Image::make($image->path())
                ->fit(200,200)
                ->save($destPath.'/'.$filename);// criar a imagem

                $user = User::find($this->loggedUser['id']);// pegar o usuario logado
                $user->avatar = $filename;
                $user->save();

                $array['url']= url('/media/avatar/'.$filename);




            }else{
                $array['error'] = 'Arquivo não suportado';            }
        }else{
            $array['error'] = 'Arquivo não encontrado!';
        }

        return $array;
    }

    public function updateCover(Request $request){
         $array = ['error'=>''];
        $typesImage = ['image/jpg', 'image/jpeg', 'image/png'];

        $image = $request->file('cover');

        if($image){
            if(in_array($image->getClientMimeType(), $typesImage)){
                $filename = md5(time().rand(0,9999)).'.jpg'; // gerar o nome do arquivo

                $destPath = public_path('/media/cover'); // destino

                $img = Image::make($image->path())
                ->fit(850,310)
                ->save($destPath.'/'.$filename);// criar a imagem

                $user = User::find($this->loggedUser['id']);// pegar o usuario logado
                $user->cover = $filename;
                $user->save();

                $array['url']= url('/media/cover/'.$filename);




            }else{
                $array['error'] = 'Arquivo não suportado';            }
        }else{
            $array['error'] = 'Arquivo não encontrado!';
        }

        return $array;

    }
}
