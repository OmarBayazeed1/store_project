<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $name = $request->query('title');
        $categoryQuery = Category::query();

        if ($name) {
            $categoryQuery = $categoryQuery->where('title', $name);
        }

        $categoryQuery = $categoryQuery->get();

        return response()->json([
            'success' => 1,
            'message' => 'Indexed successfully!',
            'data' => $categoryQuery,
        ], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => ['required', 'string', 'max:255',Rule::unique('categories')],
        ];

        $input['title'] = $request->input('title');
        $image_name = 'default.jpg';

        if ($request->hasFile('image')) {
            $destination_path = 'public/images/categories';
            $image = $request->file('image');
            // $date = date('Y-m-d H:i:s');

            $image_name = implode('.', [
                md5_file($image->getPathname()),
                $image->getClientOriginalExtension()
            ]);

            $path = $request->file('image')->storeAs($destination_path, $image_name);
            // dd($path);
        }

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->all()
            ], 422);
        }

        $category = Category::query()
            ->create([
                'title' => $input['title'],
                'img_url' => $image_name
            ]);

        return response()->json([
            'success' => 1,
            'message' => 'Created successfuly!',
            'data' => $category
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
    public function searchByCategoryTitle(Request $request){
        try
        {
            $category=Category::where('title', 'like', '%' . $request->title . '%')->get();
            return response()->json([
                'success'=>1,
                'message'=>'Searched by title successfully!',
                'data'=>$category
            ]);
        }catch(\Exception $exception){
            return response()->json([
                'success'=>0,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

}
