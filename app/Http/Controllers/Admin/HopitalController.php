<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hopital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HopitalController extends Controller
{
    public function index()
    {
        $hopitaux = Hopital::with('user')->latest()->paginate(15);
        return view('admin.hopitaux.index', compact('hopitaux'));
    }

    public function create()
    {
        return view('admin.hopitaux.create');
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
            'role'     => 'hopital',
            'ville'    => $request->ville,
            'telephone'=> $request->telephone,
        ]);

        Hopital::create([
            'user_id'   => $user->id,
            'nom'       => $request->nom,
            'adresse'   => $request->adresse,
            'ville'     => $request->ville,
            'region'    => $request->region,
            'telephone' => $request->telephone,
        ]);

        return redirect()->route('admin.hopitaux.index')->with('success', 'Hôpital créé avec succès.');
    }

    public function edit(Hopital $hopital)
    {
        return view('admin.hopitaux.edit', compact('hopital'));
    }

    public function update(Request $request, Hopital $hopital)
    {
        $request->validate([
            'nom'       => 'required|string|max:255',
            'adresse'   => 'required|string',
            'ville'     => 'required|string|max:100',
            'region'    => 'nullable|string|max:100',
            'telephone' => 'nullable|string|max:20',
            'actif'     => 'boolean',
        ]);

        $hopital->update($request->only('nom', 'adresse', 'ville', 'region', 'telephone'));
        $hopital->update(['actif' => $request->boolean('actif', true)]);
        $hopital->user->update(['name' => $request->nom, 'ville' => $request->ville]);

        return redirect()->route('admin.hopitaux.index')->with('success', 'Hôpital mis à jour.');
    }

    public function destroy(Hopital $hopital)
    {
        $hopital->user->delete();
        return redirect()->route('admin.hopitaux.index')->with('success', 'Hôpital supprimé.');
    }
}

