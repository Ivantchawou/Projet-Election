<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *Filtres sur les votes selon le critere statut
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $votes = [];
        $message = '';
        if (isset($request->statut) && !isset($request->user_id)){
            //l'electeur filtre sur toutes les requetes
            switch ($request->statut){
                case ('plan'):
                    $votes = Vote::where('statut','=','plan')
                        ->get();
                    $message = 'Votes plannifiés ';
                    break;
                case ('pending'):
                    $votes = Vote::where('statut','=','pending')
                        ->get();
                    $message = 'Votes en Cours ';
                    break;
                case('complete'):
                    $votes = Vote::where('statut','=','complete')
                        ->get();
                    $message = 'Votes Terminés ';
                    break;
                default:
                    break;
            }
            foreach ($votes as $vote){
                $vote->candidats;
            }
            return response()->json([
                'messages' => $message,
                'data' => $votes]);
        }
        elseif (isset($request->user_id) && !isset($request->statut)){
            //l'organisteur filtre sur ses votes
            switch ($request->statut){
                case ('plan'):
                    $votes = Vote::where('statut','=','plan')
                        ->where('user_id',Auth::user()->id)
                        ->get();
                    $message = 'Votes plannifiés ';
                    break;
                case ('pending'):
                    $votes = Vote::where('statut','=','pending')
                        ->where('user_id',Auth::user()->id)
                        ->get();
                    $message = 'Votes en Cours ';
                    break;
                case('complete'):
                    $votes = Vote::where('statut','=','complete')
                        ->where('user_id',Auth::user()->id)
                        ->get();
                    $message = 'Votes Terminés ';
                    break;
                default:
                    break;
            }
            foreach ($votes as $vote){
                $vote->candidats;
            }
            return response()->json([
                'message' => $message,
                'data' => $votes]);
        }

        $votes = Vote::all();
        foreach ($votes as $vote){
            $vote->candidats;
        }
        return response()->json([
            'message' => $message,
            'data' => $votes
        ]);
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
            'title' => 'required|string|max:255|unique:votes',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_hour' => 'required|date_format:H:i',
            'statut'=>'required|string|in:plan,pending,complete',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $vote = new Vote($request->all());
        //$vote->user_id = Auth::id();
        $vote->save();

        return response()->json( $vote->id, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        $voteId = $vote->id;

        $electeurs = DB::table('vote_electeurs')
            ->select('electeur_id')
            ->where('vote_id', $voteId)
            ->distinct()
            ->get();
        $candidats = DB::table('vote_candidats')
            ->select('candidat_id')
            ->where('vote_id', $voteId)
            ->distinct()
            ->get();

        $electeursParCandidat = DB::table('vote_electeurs')
            ->select('candidat_id', DB::raw('COUNT(*) as total'))
            ->where('vote_id', $voteId)
            ->groupBy('candidat_id')
            ->get();

        $response = [
            'electeurs' => $electeurs,
            'candidats' => $candidats,
            'electeursParCandidat' => $electeursParCandidat
        ];

        return response()->json(['vote' => $response]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        $vot = Vote::find($vote->id);
        if (!$vot) {
            return response()->json(['error' => 'Vote not found'], 404);
        }

            $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'string',
            'start_date' => 'date',
            'end_date' => 'date',
            'start_hour' => 'date_format:H:i',
            'end_hour' => 'date_format:H:i|after:start_hour',
            'statut'=>'required|string|in:plan,pending,complete'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $vot->fill($request->all());
        $vot->save();

        return response()->json(['data' => $vot]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        $vot = Vote::find($vote->id);
        if (!$vot) {
            return response()->json(['error' => 'Vote not found'], 404);
        }
        $vot->delete();
        return response()->json(['message' => 'Vote deleted successfully']);

    }
}
