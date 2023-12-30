<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "medications";
    protected $fillable = [
        'quantity',
        'medication_id',
        'user_id',

    ];
    protected $primaryKey = "id";
    public $timestamps = true;
    public function medicaiton(){
        return $this->belongsTo(Medication::class,'medication_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function orders_conditions(){
        return $this->hasMany(Order_Condition::class);
    }
}
