<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

use function Flasher\Toastr\Prime\toastr;

class AuthController extends Controller
{

    public function __construct() {}

    public function index()
    {
        // dd(Auth::id());
        if (Auth::id() > 0) {
            return redirect()->route('dashboard.index');
        }
        return view('backend.auth.login');
    }
    public function login(AuthRequest $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
        if (Auth::attempt($credentials)) {
            flash()->success('Login success');
            return redirect()->route('dashboard.index');
        } else {
            flash()->error('Something went wrong');
            return back();
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('auth.admin'));
    }
}
