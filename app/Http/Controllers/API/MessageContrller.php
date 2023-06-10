<?php

namespace App\Http\Controllers\API;

use App\Events\Message;
use App\Http\Controllers\Controller;
use App\Models\Message as ModelsMessage;
use App\Models\User;
use Illuminate\Http\Request;

class MessageContrller extends Controller
{
    public function message(Request $request)
    {
        $mess = new ModelsMessage($request->all());
        $admin = User::where('role_as',1)->get();
        $mess->id_admin = $admin[0]->id;
        
        $mess->save();  
        $all_mess = ModelsMessage::where('user_id',$request->user_id)->get();
        Message::dispatch($all_mess,$request->user_id);
        return ;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $mess = ModelsMessage::where('user_id',$id)->get();
        return response()->json([
            'message'=> $mess,
            'status'=>200
        ]);

    }
    public function getMessageUser()
    {
        // $mess = ModelsMessage::with('user:id,name')->get();
        $mess = ModelsMessage::with('user')->orderBy('id', 'desc')->get();

$senders = $mess->groupBy('user.id')->map(function ($messages) {
    return $messages->first()->user->name;
});

        return response()->json([
            'message'=> $senders,
            'status'=>200
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
}
