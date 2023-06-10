<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Ratting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Json;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $food = Food::all();
        return response()->json([
            'status'=> 200,
            'foods'=> $food,
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
        $validator = Validator::make($request->all(),[
            'name'=> 'required',
            'img_food'=> 'required',
            'detail'=> 'required',
            'price'=> 'required',
            'quantity'=> 'required',
            'category_id'=> 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'validator_error'=>$validator->errors()->messages(),
            ]);
        }else {
            try {
                $file = $request->file('img_food');
                $fileName = time() . $file->getClientOriginalName();
                $file->move('uploads/foods/', $fileName);
                
            } catch (\Throwable $th) {
                return response()->json([
                    'img'=>$request->all(),
                    'img2'=>$request->img_food,
                    'validator_error'=>$validator->errors()->messages(),
                ]);
            }
               
            $foods = new Food($request->all());
            $foods->img_food = $fileName;
            $foods->save();
            return response()->json([
                'status'=> 200,
                'message'=>'Add Product Successfully',
            ]);
        }
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
        $food = Food::with('category:id,category_name')->find($id);
        $rating = Ratting::with('user:id:name')->where('food_id',$id)->get();
            return response()->json([
                'status' => 200,
                'rating'=>$rating->toJson(),
                'foods' => $food->toJson(),
            ]);
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
        $food = Food::find($id)->delete();
        return response()->json([
            'status'=>200,
        ]);
    }
    public function getTop12FoodsHot(){
        $food = Food::orderBy('id','desc')->take(12)->get();
        return response()->json([
            'status'=> 200,
            'food_lm12'=> $food
        ]);
    }
    public function classify(Request $request)
    {
       
        $max = (int)$request->maxPrice;
        $min = (int)$request->minPrice;
        $food = Food::whereBetween('price',[$min, $max]); 
        if ($request->category_id[0] != null) {
            $categoryIds = [];
            $categoryIds2 = [];
            foreach ($request->category_id as $value) {
                array_push($categoryIds,$value);
            }
            
            $temp = explode(',',$categoryIds[0]);
            for ($i=0; $i < count($temp); $i++) { 
                array_push($categoryIds2,(int)$temp[$i]);
            }
            $food = $food->whereIN('category_id', $categoryIds2);
            
        }
        $food = $food->where('name','LIKE','%'.$request->search.'%');
        if ($request->arrage == 'increase') {
            
            $food = $food->orderBy('price', 'asc');
            
        } else if ($request->arrage == 'reduce'){
            $food = $food->orderBy('price', 'desc');

        }

        $food = $food->get();
            return response()->json([
                'status'=> 200,
                're'=>$request->search,
                'foods'=>  $food
            ]);
    }
}
