<?php
/**
 * Created by PhpStorm.
 * User: smartit-9
 * Date: 22.03.18
 * Time: 18:17
 */

namespace App\Controllers;


use Engine\Request\Request;
use App\Model\User;

class HomeController
{

    public function index(Request $request)
    {
        $title = 'Home Controller';
        $user = new User();

        $users = $user->orderBy('id','desc')->first();

        print_r($users->id);

        return view('home',['title' => $title]);
    }

}