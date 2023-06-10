<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $comment = Comment::with('user:id,name')->with('reply.user:id,name')->where('food_id',$request->food_id)->get();
        
        return response()->json([
            'status' => 200,
            'comments'=>$comment->toJson()

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

        $comment = new Comment($request->all());

        $comment->save();

        $commentAll = Comment::with('user:id,name')->where('food_id',$request->food_id)->get();
       
        return response()->json([
            'status' => 200,
            'comments'=>$commentAll->toJson()

        ]);
    }
    public function reply(Request $request)
    {
        $comment = new Comment($request->all());

        $comment->save();
        $commentAll = Comment::with('user:id,name')->where('food_id',$request->food_id)->get();
       
        return response()->json([
            'status' => 200,
            'comments'=>$commentAll->toJson()

        ]);
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
        $checkComment = DB::table('comments')->where('product_id', '=', $request->product_id)->where('id', $request->id)->get();

        if (count($checkComment) > 0) {
            $comment = Comment::where('product_id', $request->product_id)->where('id', $request->id)->first();

            $comment->content = $request->content;
            $comment->user_id_reply = $request->user_id_reply;
            $comment->save();
        }
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
