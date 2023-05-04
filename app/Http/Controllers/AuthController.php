<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $organisateurs =  NULL;
        if(isset($request->role) && $request->role === 'candidat'){
            $organisateurs = User::where('role','=','candidat')->get();
            return response()->json($organisateurs);
        }
        return response()->json([]);
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
            'telephone' => 'required|string|unique:users|max:255',
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
            'pieces_jointes' => 'nullable',
            'role' => 'required|string|in:candidat,admin,organisateur,electeur'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $reseaux = [];
        if (isset($request->reseaux_sociaux)) {
            foreach ($request->reseaux_sociaux as $reseau){
                $reseaux[] = $reseau;
            }
        }

        $pieces = [];
        if($request->hasFile('pieces_jointes')){
            foreach ($request->file('pieces_jointes') as $piece){
                $fileName = time().'_'.$piece->getClientOriginalName();
                $filePath = $piece->storeAs('pieces', $fileName, 'public');
                $pieces[] = $filePath;
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
            'photo' => $request->hasFile('photo') ? $request->file('photo')->storeAs('photos', time().'_'.$request->file('photo')->getClientOriginalName(), 'public') : null,
            'poste_presente_cdt' => $request->poste_presente_cdt,
            'nom_parti_politique_cdt' => $request->nom_parti_politique_cdt,
            'exp_politique_cdt' => $request->exp_politique_cdt,
            'exp_pro_cdt' => $request->exp_pro_cdt,
            'niveau_etude_cdt' => $request->niveau_etude_cdt,
            'domaine_etude_cdt' => $request->domaine_etude_cdt,
            'realisations' => $request->realisations,
            'reseaux_sociaux' => $reseaux,
            'role' => $request->role,
            'pieces_jointes' => $pieces,
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
        return response()->json($user);
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

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            $success['token'] = $authUser->createToken('MyAuthApp')->plainTextToken;
            $success['name'] = $authUser->complete_name;
            $success['id'] = $authUser->id;
            $success['role'] =   $authUser->isElecteur() ? "electeur" :
                                 ($authUser->isCandidat() ? "candidat":
                                 ($authUser->isOrganisateur() ? "organisateur" :
                                 "admin"));


            return response()->json([
                "message" => "User signed in ",
                "user_id" => $success['id'],
                "username"=>$success['name'],
                "role"=>$success['role'],
                "acces_token"=>$success['token'],
            ], 200);
        }
        else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function check_email(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-RapidAPI-Key' => '941fda43f1msh261969b320772a8p14ff81jsn42829015de4a',
                'X-RapidAPI-Host' => 'email-verifier-validator.p.rapidapi.com'
            ])->get('https://email-verifier-validator.p.rapidapi.com/', [
                'email' => $request->email,
            ]);

            if ($response->successful()) {
                return $response->json();
                // Handle successful response
            } else {
              return $response->json()['message'] ?? 'An error occurred';
                // Handle error response
            }


        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
