<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;
use Image;

class FeedController extends Controller
{
    private $loggedUser;
     public function __construct(){
        $this->middleware('auth:api');// tem que estar logado para usar esse carinha

        $this->loggedUser = auth()->user();// Pegar asinformações do meu usuario
    }

    public function create(Request $request){
        $type = $request->input('type');
        $body = $request->input('body');
        $photo = $request->file('photo');
        $typesImage = ['image/jpg', 'image/jpeg', 'image/png'];

         if($photo){
            if(in_array($photo->getClientMimeType(), $typesImage)){
                $filename = md5(time().rand(0,9999)).'.jpg';

                $destPath = public_path('/media/uploads');

                $img = Image::make($photo->path())
                ->resize(800,null,function($contraint){
                    $contraint->aspectRatio();// manter a proporção
                })
                ->save($destPath.'/'.$filename);// criar a imagem


              $body = $filename;
              $array['url']= url('/media/uploads/'.$filename);
        }
    }
        $newPost = new Post();
        $newPost->id_user = $this->loggedUser['id'];
        $newPost->type = $type;
        $newPost->body = $body;
        $newPost->created_at = date('Y-m-d H:i:s');


        $newPost->save();


        return response()->json(['message'=>'Postagem criada com sucesso'],201);

    }
}
