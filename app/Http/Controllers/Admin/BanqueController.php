<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BanqueDeSang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class BanqueController extends Controller
{
    public function index()
    {
        $banques = BanqueDeSang::with(['user', 'stocks'])->latest()->paginate(15);
        return view('admin.banques.index', compact('banques'));
    }

    public function create()
    {
        return view('admin.banques.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'       => 'required|string|max:255',
            'adresse'   => 'required|string',
            'ville'     => 'required|string|max:100',
            'region'    => 'nullable|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8',
        ]);

        $user = User::create([
            'name'     => $request->nom,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'banque_de_sang',
            'ville'    => $request->ville,
            'telephone'=> $request->telephone,
        ]);

        BanqueDeSang::create([
            'user_id'   => $user->id,
            'nom'       => $request->nom,
            'adresse'   => $request->adresse,
            'ville'     => $request->ville,
            'region'    => $request->region,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('admin.banques.index')->with('success', 'Banque de sang créée avec succès.');
    }

    public function edit(BanqueDeSang $banque)
    {
        return view('admin.banques.edit', compact('banque'));
    }

    public function update(Request $request, BanqueDeSang $banque)
    {
        $request->validate([
            'nom'       => 'required|string|max:255',
            'adresse'   => 'required|string',
            'ville'     => 'required|string|max:100',
            'region'    => 'nullable|string|max:100',
            'telephone' => 'nullable|string|max:20',
        ]);

        $banque->update($request->only('nom', 'adresse', 'ville', 'region', 'telephone'));
        $banque->update(['actif' => $request->boolean('actif', true)]);
        $banque->user->update(['name' => $request->nom, 'ville' => $request->ville]);

        return redirect()->route('admin.banques.index')->with('success', 'Banque de sang mise à jour.');
    }

    public function destroy(BanqueDeSang $banque)
    {
        $banque->user->delete();
        return redirect()->route('admin.banques.index')->with('success', 'Banque de sang supprimée.');
    }
}

