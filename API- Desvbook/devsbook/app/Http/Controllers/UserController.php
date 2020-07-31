<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
     private $loggedUser;
     public function __construct(){
        $this->middleware('auth:api');// tem que estar logado para usar esse carinha

        $this->loggedUser = auth()->use();// Pegar asinformações do meu usuario
    }
}
