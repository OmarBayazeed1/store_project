<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Medication;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders=Order::all();

        foreach ($orders as $order){
            $user_id=$order->id;
            $user_name=User::query()->where('id','=',$user_id)->value('username');
            $order['username']=$user_name;
        }
        foreach ($orders as $order){
            $medication_id=$order->id;
            $medication_name=Medication::query()->where('id','=',$medication_id)->value('commercial_name');
            $order['commercial_name']=$medication_name;
        }

        return response()->json([
            'success' => 1,
            'message' => 'Indexed successfully!',
            'data' => $orders,
        ], 200);
    }

    public function storeOrderByUser(Request $request): JsonResponse
    {
        $rules=[
            'user_id' => ['required','numeric'],
            'medication_id'=>['required','numeric'],
            'quantity'=>['numeric','min:1', 'nullable'],
            //'payment'=>['required','boolean'],
           // 'status' => ['required','in:Preparing,Sent,Received']
        ];

        $input['user_id']=$request->input('user_id');
        $input['medication_id']=$request->input('medication_id');
        $input['quantity']=$request->input('quantity');

        // $input['payment']=$request->input('payment');
       // $input['status']=$request->input('status');

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->all()
            ], 422);
        }
        $orders = Order::query()
            ->create([
                'user_id' => $input['user_id'],
                'medication_id' => $input['medication_id'],
                'quantity' => $input['quantity'],

                //'payment' => $input['payment'],
                //'status' => $input['status'],

            ]);
        foreach ($orders as $order){
            $user_id=$order->id;
            $user_name=User::query()->where('id','=',$user_id)->value('username');
            $order['username']=$user_name;
        }
        foreach ($orders as $order){
            $medication_id=$order->id;
            $medication_name=Medication::query()->where('id','=',$medication_id)->value('commercial_name');
            $order['commercial_name']=$medication_name;
        }
        return response()->json([
            'success' => 1,
            'message' => 'Created Order successfully!',
            'data' => $orders
        ], 201);

        /*
         * // Validate the request data
        $request->validate([
            'user_id' => ['required'],
            'payment'=>['required','boolean'],
            'status' => ['required','in:Preparing,Sent,Received']
        ]);

        // Create a new order
        $order = Order::create($request->all());

        // Return a response
        return response()->json([
            'success'=>1,
            'message'=>"Order Created Successfully",
            'data'=>$order
        ], 201);
         *
         * */
    }

    public function storeOrder(Request $request){
        $validatedata=$request->validate([
            'user_id'=>['required','numeric'],
            'medication_id'=>['required','numeric'],
            'quantity'=>['numeric','min:1', 'nullable']
        ]);
        $order=Order::query()->create([
            'user_id'=>$request['user_id'],
            'medication_id'=>$request['medication_id'],
            'quantity'=>$request['quantity'],

        ]);
        $user_id=$order->id;
        $user_name=User::query()->where('id','=',$user_id)->value('username');
        $order['username']=$user_name;

        $medication_id=$order->id;
        $medication_name=Medication::query()->where('id','=',$medication_id)->value('commercial_name');
        $order['commercial_name']=$medication_name;

        return response()->json([
            'success' => 1,
            'message' => 'Created Order successfully!',
            'data' => $order
        ], 201);
    }
    public function destroy(Order $order, $id): JsonResponse
    {
        $order = Order::query()->find($id);

        if ($order) {
            $order->delete();

            return response()->json([
                'success' => 1,
                'message' => 'Destroyed Order successfully!',
            ], 202);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'Invalid Order ID!',
            ], 404);
        }
    }

    // Method to retrieve all orders belonging to a user
    public function getByUser($userId)
    {
        // Retrieve all orders for the user
        $orders = Order::where('user_id', $userId)->get();

        // Return a response
        return response()->json([
            'success' => 1,
            'message' => 'Showed Orders successfully!',
            'data' => $orders
        ], 201);
    }

    //admin
    public function updatePayment(Request $request, $id)
    {
        // Find the order by ID
        $order = Order::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'payment' => 'required|boolean',
        ]);

        // Update the payment
        $order->updatePayment($validatedData['payment']);

        return response()->json([
            'success' => 1,
            'message' => 'Update Payment successfully!',
            'data' => $order
        ], 200);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        // Find the order by ID
        $order = Order::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'status' => ['required','in:Preparing,Sent,Received'],
        ]);

        // Update the order status
        $order->updateOrderStatus($validatedData['status']);
        // If the order status is "Sent," update the quantities of medications automatically
        if ($validatedData['status'] === 'Sent') {
            // Get the medications associated with the order
            $medications = Medication::whereHas('orders', function ($query) use ($id) {
                $query->where('id', $id);
            })->get();

            // Update the quantities of medications
            foreach ($medications as $medication) {
                $medication->decrement('quantity', $order->quantity);
            }
        }

        return response()->json([
            'success' => 1,
            'message' => 'Update Order Status successfully!',
            'data' => $order
        ], 200);
    }


}

