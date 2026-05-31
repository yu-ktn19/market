<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create(RegisterRequest $request)
    {
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        Auth::login($user);

        $user->sendEmailVerificationNotification();
        
        return redirect('/email/verify');
    }
   
    public function login(LoginRequest $request)
    { 
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            if (!Auth::user()->hasVerifiedEmail()) {
                return redirect('/email/verify');
            }

        return redirect('/');
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->onlyInput('email');
    }

    public function mypage(Request $request)
    {
        $user = Auth::user();

        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $items = Item::whereHas('purchases', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();
        } else {
            $items = Item::where('user_id', $user->id)->get();
        }

        return view('page', compact('user', 'items', 'page'));
    }

    public function edit()
    {
        $user = Auth::user();  

        return view('profile', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $data = [
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ];

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $path;
        }

        $user->update($data);

        return redirect('/');
    }

    public function logout(){
        return view('auth.login');
    }
}
