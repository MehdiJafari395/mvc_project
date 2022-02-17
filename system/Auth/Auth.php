<?php

namespace System\Auth;

use App\Users;
use System\Session\Session;

class Auth
{
    private $redirectTo = '/login';

    private function userMethod()
    {
        if(!Session::get('user')){
            return redirect($this->redirectTo);
        }
        $user = Users::find(Session::get('user'));
        if(empty($user)){
            Session::remove('user');
            return redirect($this->redirectTo);
        }
        return $user;
    }

    private function checkMethod()
    {
        if(!Session::get('user')){
            return redirect($this->redirectTo);
        }
        $user = Users::find(Session::get('user'));
        if(empty($user)){
            Session::remove('user');
            return redirect($this->redirectTo);
        }
        return true;
    }

    private function checkLoginMethod()
    {
        if(!Session::get('user')){
            return false;
        }
        $user = Users::find(Session::get('user'));
        if(empty($user)){
            Session::remove('user');
            return false;
        }
        return true;
    }

    private function loginByEmailMethod($email, $password)
    {
        $user = Users::where('email', $email)->get();
        if(empty($user)){
            error('login', 'there is not user with this email');
            return false;
        }
        if(password_verify($password, $user[0]->password) AND $user[0]->is_active == 1){
            Session::set('user', $user[0]->id);
            return true;
        }
        error('login', 'the input password is incorrect');
        return false;
    }

    private function loginByIdMethod($id)
    {
        $user = Users::find($id);
        if(empty($user)){
            error('login', 'there is not user');
            return false;
        }
        Session::set('user', $user->id);
        return true;
    }

    private function logoutMethod()
    {
        $user = Session::get('user');
        if(empty($user)){
            error('logout', 'user is not login');
            return false;
        }
        Session::remove('user');
        return true;
    }

    public function __call($name, $arguments)
    {
        return $this->methodCaller($name, $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
         $instance = new self();
         return $instance->methodCaller($name, $arguments);
    }

    private function methodCaller($method, $arguments)
    {
        $suffix = 'Method';
        $methodName = $method.$suffix;
        return call_user_func_array([$this, $methodName], $arguments);
    }
}