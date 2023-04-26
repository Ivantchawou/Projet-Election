<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vote;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $votes = Vote::all();
        return response()->json(['data' => $votes]);
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_hour' => 'required|date_format:H:i',
            'statut'=>'required|string|in:plan,pending,complete',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $vote = new Vote($request->all());
        $vote->user_id = Auth::id();
        $vote->save();

        return response()->json(['data' => $vote], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        $vot = Vote::find($vote->id);
        if (!$vot) {
            return response()->json(['error' => 'Vote not found'], 404);
        }
        return response()->json(['data' => $vot]);

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
