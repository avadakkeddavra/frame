<?php
namespace App\Controllers;
use App\Model\User;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('web');
    }

    public function index()
    {
        $title = 'Home Controller';
        $user = new User();

        $users = $user->orderBy('id','desc')->first();

        print_r($users->id);

        return view('home',['title' => $title]);
    }

}