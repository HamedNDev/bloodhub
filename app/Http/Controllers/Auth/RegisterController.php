<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Donneur;
use App\Models\Hopital;
use App\Models\BanqueDeSang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:8|confirmed',
            'telephone'     => 'nullable|string|max:20',
            'role'          => 'required|in:patient,donneur,hopital,banque_de_sang',
            'groupe_sanguin'=> 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'ville'         => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'telephone'     => $request->telephone,
            'role'          => $request->role,
            'groupe_sanguin'=> $request->groupe_sanguin,
            'ville'         => $request->ville,
        ]);

        match ($user->role) {
            'donneur'       => Donneur::create(['user_id' => $user->id]),
            'hopital'       => Hopital::create(['user_id' => $user->id, 'nom' => $user->name, 'adresse' => '', 'ville' => $user->ville ?? '']),
            'banque_de_sang'=> BanqueDeSang::create(['user_id' => $user->id, 'nom' => $user->name, 'adresse' => '', 'ville' => $user->ville ?? '']),
            default         => null,
        };

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}

