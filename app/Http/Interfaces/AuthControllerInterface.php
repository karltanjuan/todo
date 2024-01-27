<?php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;

interface AuthControllerInterface
{
    public function getRegister();
    public function postRegister(UserRegisterRequest $request);
    public function getLogin();
    public function postLogin(UserLoginRequest $request);
    public function logout();
}
