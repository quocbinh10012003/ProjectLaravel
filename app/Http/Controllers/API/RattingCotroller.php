<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ratting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RattingCotroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rate = Ratting::with('user:id,name')->where('food_id', '=', $request->food_id)->get();
       
        return response()->json([
            'status' => 200,
            'rate_all' => $rate->toJson(),
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
        $checkRate = DB::table('rattings')->where('food_id', '=', $request->product_id)->where('user_id', $request->user_id)->get();

        if (count($checkRate) > 0) {
            $ratting = Ratting::where('food_id', $request->product_id)->where('user_id', $request->user_id)->first();

            if ($request->content) {
                $ratting->content = $request->content;
            } else {
                $ratting->rate = $request->rate;
            }
            $ratting->save();
        } else {
            $ratting = new Ratting($request->all());
            $ratting->save();
        }

        $rate = Ratting::with('user:id,name')->where('food_id', '=', $request->product_id)->get();
        
        return response()->json([
            'status' => 200,
            'rate_all' => $rate->toJson(),

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $checkRate = DB::table('rattings')->where('food_id', '=', $request->product_id)->where('user_id', $request->user_id)->get();
        return response()->json([
            'status' => 200,
            'rate' => $checkRate[0]->rate
        ]);
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
}
