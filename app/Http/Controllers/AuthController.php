<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;

class AuthController extends BaseController
{
    public function showLogin()
    {
        return view('auth.index');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (\Auth::attempt($credentials)) {
            return redirect()->route('home');
        } else {
            return back()->with('error', 'Введен неправильно логин или пароль');
        }
    }

    public function registration(UserRequest $request)
    {
        DB::transaction(function () use ($request) {
            $input = $request->except('password');
            $user = new User($input);
            $user->password = bcrypt($request->password);
            $user->save();
            $user->createProfile();
            \Auth::login($user, true);
        });

        return back()->with('success', 'Пользователь успешно зарегистрирован.');
    }

    public function logout()
    {
        \Auth::logout();
        return back();
    }
}
