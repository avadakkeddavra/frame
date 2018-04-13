<?php
namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Engine\Request\Request;
use App\Model\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        if($request->method('POST'))
        {

            $user = User::where('login',$request->get('login'))->where('password',md5($request->get('password')))->first();
            if($user)
            {
                \Auth::login($user);
                return redirect('');
            }else{
                return view('auth.login',['error' => 'No such user']);
            }

        }else{
            return view('auth.login');
        }

    }

    public function register(Request $request)
    {
        if($request->method('POST'))
        {

            $user = User::create([
                'name' => $request->get('name'),
                'login' => $request->get('login'),
                'password'=> md5($request->get('password'))
            ]);

            \Auth::register($user);

            return redirect('');
        }else{
            return view('auth.register');
        }
    }
}