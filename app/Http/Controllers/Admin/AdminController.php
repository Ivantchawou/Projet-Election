<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;



class AdminController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validationSimple(Request $request)
    {
        if (isset($request->id)) {
            $message = $this->appelValidation($request->id);
            if ($message === true) {
                return response()->json([
                    "message" => "modification effectuer"
                ], 201);
            } else {
                return response()->json([
                    "message" => "modification non effectuer un des users n'existe pas"
                ], 404);
            }
        } else {
            return response()->json([
                "Error"
            ], 404);
        }
    }

    private function appelValidation(int $id)
    {
        $isvalide = false;
        $user = User::find($id);

        if (is_null($user)) {
            $isvalide = false;
            return $isvalide;
        } else {
            $isvalide = true;
            $user->estValide();
        }
        return $isvalide;
    }

    public function validationMultiple(Request $request)
    {
        $tableId = $request->tableId;

        if (isset($request->tableId)) {
            foreach ($tableId as $id) {
                $message = $this->appelValidation($id);
            }
        } else {

            
            return response()->json([
                "message" => "Error tableId is required"
            ], 404);
        }

        if ($message === true) {
            return response()->json([
                "message" => "modification effectuer"
            ], 201);
        } else {
            return response()->json([
                "message" => "modification non effectuer un des users n'existe pas"
            ], 404);
        }
    }
}