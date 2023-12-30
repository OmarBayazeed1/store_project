<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_Condition extends Model
{
    use HasFactory;
    protected $primaryKey = "id";
    protected $table="order_conditions";
    public $fillable=[
       'order_id',
       'condition_id'
    ];
    public $timestamps = true;

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    public function condition(){
        return $this->belongsTo(Condition::class,'condition_id');
    }

}
