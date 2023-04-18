<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complete_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'identification_number' => 'required|string|unique:users|max:255',
            'password' => 'required|string|min:8|max:255',
            'age' => 'required|integer|min:18',
            'citoyennete' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'residence' => 'required|string',
            'language' => 'required|string|in:en,fr,other',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'poste_presente_cdt' => 'nullable|string|max:255',
            'nom_parti_politique_cdt' => 'nullable|string|max:255',
            'exp_politique_cdt' => 'nullable|string',
            'exp_pro_cdt' => 'nullable|string',
            'niveau_etude_cdt' => 'nullable|string|max:255',
            'domaine_etude_cdt' => 'nullable|string|max:255',
            'realisations' => 'nullable|string',
            'reseaux_sociaux' => 'nullable|array',
            'pieces_jointes' => 'nullable|array',
            'role' => 'required|string|in:candidat,admin,organisateur,electeur'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $reseaux = null;
        if (isset($request->reseaux_sociaux)) {
            foreach ($request->reseaux_sociaux as $reseau){
                $reseaux[] = $reseau;
            }
        }

        $pieces = null;
        if(isset($request->pieces_jointes )){
            foreach ($request->pieces_jointes as $piece){
                $pieces[] = $piece;
            }
        }

        $user = User::create([
            'complete_name' => $request->complete_name,
            'email' => $request->email,
            'identification_number' => $request->identification_number,
            'password' => Hash::make($request->password),
            'age' => $request->age,
            'citoyennete' => $request->citoyennete,
            'telephone' => $request->telephone,
            'residence' => $request->residence,
            'language' => $request->language,
            'photo' => $request->hasFile('photo') ? $request->file('photo')->store('photos') : null,
            'poste_presente_cdt' => $request->poste_presente_cdt,
            'nom_parti_politique_cdt' => $request->nom_parti_politique_cdt,
            'exp_politique_cdt' => $request->exp_politique_cdt,
            'exp_pro_cdt' => $request->exp_pro_cdt,
            'niveau_etude_cdt' => $request->niveau_etude_cdt,
            'domaine_etude_cdt' => $request->domaine_etude_cdt,
            'realisations' => $request->realisations,
            'reseaux_sociaux' => json_encode($request->reseaux_sociaux),
            'role' => $request->role,
            'pieces_jointes' => json_encode($request->pieces_jointes),
        ]);

        return response()->json(['user' => $user], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function signin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => "Information invalide ou manquante",
            ], 401);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            $success['token'] = $authUser->createToken('MyAuthApp')->plainTextToken;
            $success['name'] = $authUser->complete_name;

            return response()->json([
                "message" => "User signed in ".$success['name']." ".$success['token'],
            ], 200);
        }
        else {
            return response()->json([
                "error" => "Authentication failed user unauthorises",
            ]);
        }
    }
}