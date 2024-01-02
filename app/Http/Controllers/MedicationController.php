<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Medication;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\error;

class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rules=[
            'scientific_name'=>['string','max:255', 'nullable'],
            'commercial_name'=>['string','max:255', 'nullable'],
            'manufacture'=>['string','max:255', 'nullable'],
            'price'=>['numeric','min:1', 'nullable'],
            'expiry_date'=>['date'],
            'quantity'=>['numeric','min:1', 'nullable'],
            'img_url'=>['string','max:255', 'nullable'],
            'category_id'=>['nullable']

        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->all(),
            ], 422);
        }

        $scientific_name = $request->query('scientific_name');
        $commercial_name = $request->query('commercial_name');
        $manufacture = $request->query('manufacture');
        $price = $request->query('price');
        $expiry_date = $request->query('expiry_date');
        $quantity = $request->query('quantity');
        $img_url = $request->query('img_url');
        $category_id = $request->query('category_id');


        $medicationQuery = Medication::query();

        if ($scientific_name) {
            $medicationQuery = $medicationQuery->where('scientific_name', $scientific_name);
        }
        if ($commercial_name) {
            $medicationQuery = $medicationQuery->where('commercial_name', $commercial_name);
        }
        if ($manufacture) {
            $medicationQuery = $medicationQuery->where('manufacture', $manufacture);
        }
        if ($price) {
            $medicationQuery = $medicationQuery->where('price', $price);
        }
        if ($expiry_date) {
            $medicationQuery = $medicationQuery->where('expiry_date', $expiry_date);
        }
        if ($quantity) {
            $medicationQuery = $medicationQuery->where('quantity', $quantity);
        }
        if ($img_url) {
            $medicationQuery = $medicationQuery->where('img_url', $img_url);
        }
        if ($category_id) {
            $medicationQuery = $medicationQuery->where('category_id', $category_id);
        }


        $medicationQuery = $medicationQuery->get();

        return response()->json([
            'success' => 1,
            'message' => 'Indexed successfuly!',
            'data' => $medicationQuery,
        ], 200);
    }


    public function store(Request $request)
    {
        $rules = [
            'scientific_name' => ['required', 'string', 'max:255'],
            'commercial_name' => ['required', 'string', 'max:255'],
            'manufacture'=>['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:1'],
            'expiry_date' => ['required', 'date', 'after:today'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'img_url' => ['string'],
            'category_id'=>['required','min:1']

        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->all(),
            ], 422);
        }

        // $user1 = auth()->user();
        //$user = User::find(auth()->id());

        $scientific_name = $request->input('scientific_name');
        $commercial_name = $request->input('commercial_name');
        $manufacture = $request->input('manufacture');
        $expiry_date = $request->input('expiry_date');
        $quantity = $request->input('quantity');
        $price = $request->input('price');
        $img_url = $request->input('img_url');
        $category_id = $request->input('category_id');

        $image_name = 'default.jpg';

        if ($request->hasFile('image')) {
            $destination_path = 'public/images/medications';
            $image = $request->file('image');
            // $date = date('Y-m-d H:i:s');

            $image_name = implode('.', [
                md5_file($image->getPathname()),
                $image->getClientOriginalExtension()
            ]);

            $path = $request->file('image')->storeAs($destination_path, $image_name);
        }

        $medication = Medication::create([
            'scientific_name' => $scientific_name,
            'commercial_name' => $commercial_name,
            'manufacture'=>$manufacture,
            'expiry_date' => $expiry_date,
            'quantity' => $quantity,
            'price' => $price,
            //'img_url' => $img_url,
            'img_url' => $image_name,
            'category_id' => $category_id,
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Created successfully!',
            'data' => $medication,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Medication $medication, $id)
    {
        $medication=Medication::query()->find($id);

        if (!$medication) {
            return response()->json([
                'success' => 0,
                'message' => 'invalid id',
                'data'=> $medication,
            ], 404);
        }

        return response()->json([
            'success' => 1,
            'message' => 'Showed successfully!',
            'data' => $medication,
        ], 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medication $medication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medication $medication)
    {
        //
    }

    //getMedsByCategories
    function medsByType(Request $request)
    {
        if (isset($request['id'])) {
            return Medication::getMedsByCategories($request['id']);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'Enter the id of category!',

            ], 404);
        }
    }

    function searchByMedicationName(Request $request){
        try
        {
            $medication=Medication::where('commercial_name', 'like', '%' . $request->commercial_name . '%')->get();
            return response()->json([
                'success'=>1,
                'message'=>'Searched by name successfully!',
                'data'=>$medication
            ]);
        }catch(\Exception $exception){
            return response()->json([
                'success'=>0,
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
