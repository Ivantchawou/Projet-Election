<?php

namespace App\Http\Controllers;

use App\Models\VoteElecteur;
use Exception;
use Illuminate\Http\Request;

class VoteElecteurController extends Controller
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
        try {

            $vote_electeur = new VoteElecteur();
            $vote_electeur->vote_id = intval($request->vote_id);
            $vote_electeur->electeur_id = intval($request->electeur_id);
            $vote_electeur->candidat_id = intval($request->candidat_id);
            $vote_electeur->isVoted = true;
            $vote_electeur->save();
            return response()->json(['data' => $vote_electeur], 201);

        } catch (Exception $e) {
            return response()->json($e);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoteElecteur  $voteElecteur
     * @return \Illuminate\Http\Response
     */
    public function show(VoteElecteur $voteElecteur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoteElecteur  $voteElecteur
     * @return \Illuminate\Http\Response
     */
    public function edit(VoteElecteur $voteElecteur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoteElecteur  $voteElecteur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VoteElecteur $voteElecteur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoteElecteur  $voteElecteur
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoteElecteur $voteElecteur)
    {
        //
    }
}
