<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use App\Models\Medication;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->query('medication_id');
        $medication = Medication::find($id);

        if (!$id) {
            $likes = Favourite::all();
            return response()->json([
                'success' => 1,
                'message' => 'Indexed successfully',
                'data' => $likes
            ], 200);
        }
        if (!$medication) {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid ID',
                'data' => $medication
            ], 404);
        }

        $likes = $medication->favourites()->get();

        if($likes){
            return response()->json([
                'success' => 1,
                'message' => 'Indexed successfully',
                'data' => $likes
            ], 200);
        }

        return response()->json([
            'success' => 1,
            'message' => 'No Favourites',
            'data' => $likes
        ], 200);
    }
    public function favourite(Request $request) {
        $medication_id = $request->query('medication_id');
        $user_id = $request->query('user_id');

        // $_user = auth()->user();
        // $user = User::find($_user['id']);
       // $user = User::find(auth()->id());
        $medication = Medication::find($medication_id);

        // see if this Medication is liked by this user or not
        $is_liked = false;
        $likeIsFound = $medication->favourites()->where('user_id', $user_id['id'])->first();
        if ($likeIsFound) {
            return response()->json([
                'success' => 0,
                'message' => 'Medication is liked Before',
            ], 401);
        }

        $like = $medication->favourites()->create([
            'user_id' => $user_id['id']
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Liked successfully',
        ], 200);
    }

    public function unFavourite(Request $request) {
        $medication_id = $request->query('medication_id');
        $user_id = $request->query('user_id');

        // $_user = auth()->user();
        // $user = User::find($_user['id']);
        //$user = User::find(auth()->id());
        $medication = Medication::find($medication_id);

        // see if this product is liked by this user or not
        $is_liked = false;
        # we should put ( $likes = $product->likes() )  instead of ( Like::where )
        $likeIsFound = $medication->favourites()->where('user_id', $user_id['id'])->first();
        if (!$likeIsFound) {
            return response()->json([
                'success' => 0,
                'message' => 'Medication is not Favourite Before',
            ], 401);
        }

        $like = $medication->favourites()->where('user_id', $user_id['id']);
        $like->delete();

        return response()->json([
            'success' => 1,
            'message' => 'UnFavourite successfully',
        ], 200);
    }
    public function like(Request $request){
        try{
            $request->validate([

            'medication_id'=>['required','unique:medications'],
            'user_id'=>['required']
        ]);

            $data=Favourite::query()->create([
                'medication_id'=>$request['medication_id'],
                'user_id'=>$request['user_id']
            ]);



            return response()->json([
                'status'=>1,
                'data'=>$data,
                'message'=>'Added to Favourite Successfully'
            ]);}catch (\Exception $exception) {
            return response()->json([
                'status'=>0,
                'message' => $exception->getMessage(),
            ], 400);
        }

    }

    public function addToFavorite(Request $request, $id)
    {
        try {
            $user =User::class;

            $favoirte = new Favourite();
            $favoirte->medication_id = $id;
            $favoirte->pharmacist_id = $user->id;
            $favoirte->save();
            return response()->json([
                'status' => 1,
                'message' => 'Added to your favourites',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status'=>0,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function remove(Request $request, $id)
    {
        try {
            $favoirte = Favorite::where('id', $id)->first();
            if ($favoirte) {
                $favoirte->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Has been removed',
                ]);
            }
            return response()->json([
                'status' => 'Not Found',
                'message' => 'id not found',
            ], 404);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 400);
        }
    }

    public function showAllFavorites()
    {
        try {
            $user = Auth::user()->pharmacist;
            $favoirte = Favorite::where('pharmacist_id', $user->id)->get();
            return $this->sendResponse(FavoriteResource::collection($favoirte), 'Success');
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 400);
        }
    }
}
