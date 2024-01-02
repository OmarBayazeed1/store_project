<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $fillable = [
        'payment',
        'status',
        'quantity',
        'user_id',
        'medication_id'

    ];
    public array $enum = ['Preparing', 'Sent', 'Received'];
    protected $primaryKey = "id";
    public $timestamps = true;

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function medication(){
        return $this->belongsTo(Medication::class,'medication_id');
    }

    //some functions to update status
    public function updatePayment($payment)
    {
        // Update the payment status
        $this->payment = $payment;
        $this->save();
    }

    public function updateOrderStatus($orderStatus)
    {
        // Update the order status
        $this->status = $orderStatus;
        $this->save();
    }
}
