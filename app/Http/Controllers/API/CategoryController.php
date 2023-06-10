<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Flysystem\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();

        
        return response()->json([
            'status'=>200,
            'all_categories'=> $category,
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
            'category_name'=> 'required',
            'img_category'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validator_error'=>$validator->errors()->messages(),
            ]);
        }else {
            try {
                $file = $request->file('img_category');
                $fileName = time() . $file->getClientOriginalName();
                $file->move('uploads/category/', $fileName);
            } catch (\Throwable $th) {
                return response()->json([
                    'validator_error'=>$validator->errors()->messages(),
                ]);
            }
                
            $category = new Category($request->all());
            $category->img_category = $fileName;
            $category->save();
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
        $category = Category::find($id);
        return response()->json([
            'status' => 200,
            'category' => $category,
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
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'Category not found',
            ]);
        }
        if ($request->hasFile('img_category')) {
            $filePath = public_path('uploads/category/' . $category->img_category);
            unlink($filePath);
            try {
                $file = $request->file('img_category');
                $fileName = time() . $file->getClientOriginalName();
                $file->move('uploads/category/', $fileName);
                $category->img_category = $fileName;
            } catch (\Throwable $th) {
                return response()->json([
                    'validator_error'=>'lỗi ảnh',
                ]);
            }
        }
        $category->category_name = $request->category_name;
        $category->save();
        return response()->json([
            'status'=> 200,
            'message'=>'Add Product Successfully',
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
        $category = Category::find($id);
        $filePath = public_path('uploads/category/' . $category->img_category);
        unlink($filePath);
        $category2 = Category::find($id)->delete();
        
        return response()->json([
            'status'=>200,
        ]);
    }
    public function search(Request $request)
    {
        $category = DB::table('categories')->where('category_name','LIKE', '%'.$request->search.'%')->get();
        return response()->json([
            'status'=>200,

            'data_search' => $category
        ]);
    }
}
