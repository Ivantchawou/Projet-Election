<?php

namespace App\Http\Controllers;

use App\Models\VoteCandidat;
use Illuminate\Http\Request;

class VoteCandidatController extends Controller
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
            $vote_id = $request->vote_id;
            $candidat_ids = $request->candidat_ids;
            foreach ($candidat_ids as $key => $value) {
                $vote_candidat = new VoteCandidat();
                $vote_candidat->vote_id = $vote_id;
                $vote_candidat->candidat_id = intval($value);
                $vote_candidat->save();
            }
        return response()->json(['message' => 'creation success'], 201);

        } catch (Exception $e) {
            return response()->json($e);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VoteCandidat  $voteCandidat
     * @return \Illuminate\Http\Response
     */
    public function show(VoteCandidat $voteCandidat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VoteCandidat  $voteCandidat
     * @return \Illuminate\Http\Response
     */
    public function edit(VoteCandidat $voteCandidat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VoteCandidat  $voteCandidat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VoteCandidat $voteCandidat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VoteCandidat  $voteCandidat
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoteCandidat $voteCandidat)
    {
        //
    }
}
