<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use Validator;
use Session;
use Auth;
use Hash;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{

    public function getRegister()
    {
        if (auth()->check()) {
            return redirect('tasks');
        }

        return view('register');
    }

    public function postRegister(UserRegisterRequest $request)
    {       
        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($user) {
            return response()->json([
                'message' => 'User created successfully',
                'code'    => '200'
            ]);
        }

    }

    public function getLogin()
    {
        if (auth()->check()) {
            return redirect('tasks');
        }

        return view('login');
    }

    public function postLogin(UserLoginRequest $request)
    {
        $response = response()->json(['errors' => [
            'email' => ['Invalid email or password']]
        ], 422);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = auth()->user();
            return response()->json(['message' => 'Login successfully', 'code' => '200']);
        }

        return $response;
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('login');
    }
  
}