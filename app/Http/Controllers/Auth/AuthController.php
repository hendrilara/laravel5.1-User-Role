<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use App\Models\User;
use App\Models\Role;
use Input;
use Hash;
use Auth;
use Redirect;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    protected $auth;


    public function __construct( Guard $auth)
    {
        $this->auth = $auth;
      //  $this->middleware('auth');
    }

    public function getLogin()
    {
        return view('auth/login');
    }

    public function postLogin()
    {
        $email      = Input::get('email');
        $password   = Input::get('password');
        $remember   = Input::get('remember');

        if($this->auth->attempt([
            'email'     => $email,
            'password'  => $password
        ], $remember == 1 ? true : false))
        {
        if( $this->auth->user()->hasRole('customer'))
        {
            return redirect()->route('user.home');
        }

        if( $this->auth->user()->hasRole('administrator'))
        {
            return redirect()->route('admin.home');
        }

        }
        else
        {
            return redirect()->back()
                ->with('message','Incorrect email or password')
                ->with('status', 'danger')
                ->withInput();
        }

    }

    public function getRegister()
    {
        return view('auth/register');
    }

    public function postRegister()
    {

        $user = new User();
        $user->name     = Input::get('name');
        $user->email    = Input::get('email');
        $user->password = Hash::make(Input::get('password'));

        $user->save();
        //Assign Role
        $role = Role::whereName('customer')->first();
        $user->assignRole($role);

        return redirect('auth/login');

    }

    public function getLogout()
    {
        \Auth::logout();
        return redirect('/');
    }

}