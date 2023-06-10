<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::all();
        return response()->json([
            'status' => 200,
            'blogs'=> $blogs
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
            'title'=> 'required',
            'description'=> 'required',
            'img_blog'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validator_error'=>$validator->errors()->messages(),
            ]);
        }else {
            try {
                $file = $request->file('img_blog');
                $fileName = time() . $file->getClientOriginalName();
                $file->move('uploads/blogs/', $fileName);
                
            } catch (\Throwable $th) {
                return response()->json([
                    'validator_error'=>$validator->errors()->messages(),
                ]);
            }
                
            $blog = new Blog($request->all());
            $blog->img_blog = $fileName;
            $blog->save();
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
