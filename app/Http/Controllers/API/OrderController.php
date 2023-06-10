<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_user)
    {  
          $order = Order::with('food')->where('user_id',$id_user)->get();
            return response()->json([
                'status' =>200,
                'order'=>$order->toJson(),
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
        $checkOrder = DB::table('orders')->where('food_id','=',$request->food_id)->where('user_id', '=',$request->user_id)->get();
        
        if (count($checkOrder) >0) {
            $order = Order::where('food_id', $request->food_id)->where('user_id',$request->user_id)->where('activity',0)->first();
            $order2 = DB::table('orders')->where('food_id', $request->food_id)->where('user_id',$request->user_id)->where('activity',0)->get();
           
        
            $food = Food::find($request->food_id);
            $order->price = $food->price;
            $order->quantity = (($request->quantity)? $request->quantity+ $order2[0]->quantity : $order2[0]->quantity+1);
            $order->total = ($request->quantity + $order2[0]->quantity) * $order2[0]->price;
            $order->save();
        }else{

            $order = new Order($request->all());
            $food = Food::find($request->food_id);
            $order->price = $food->price;
            $order->total = $food->price* $request->quantity;
           
            $order->save();
        }
       
        $order = Order::with('food')->where('user_id',$request->user_id)->get();
            return response()->json([
                'status' =>200,
                'order'=>$order->toJson(),
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
    public function update(Request $request)
    {
        $order = Order::where('food_id', $request->food_id)->where('user_id',$request->user_id)->first();
        $order2 = DB::table('orders')->where('food_id', $request->food_id)->where('user_id',$request->user_id)->get();
       
    
        $food = Food::find($request->food_id);
        $order->price = $food->price;
        $order->quantity =  $request->quantity;
        $order->total = $request->quantity  * $order2[0]->price;
        $order->save();
        $order = Order::with('food')->where('user_id',$request->user_id)->get();
            return response()->json([
                'status' =>200,
                'order'=>$order->toJson(),
            ]);
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
    public function assert(Request $request){
        $validate = Validator::make($request->all(),[
            'food_id'=>'required',
            'address'=>'required',
            'phone_number'=>'required',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'validation_error'=>$validate->errors()->messages(),
            ]);
        }else{
            
            
            $order = Order::where('user_id',$request->user_id)->whereIn('food_id',explode(",", $request->food_id))->update(['activity'=>1]);
           $user = User::find($request->user_id);
           $user->phone_number = $request->phone_number;
           $user->address = $request->address;
           $user->save();
            return response()->json([
                'status'=>200
            ]);
        }
    }

}
