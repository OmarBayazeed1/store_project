<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;
    protected $table = "medications";
    protected $fillable = [
        'scientific_name',
        'commercial_name',
        'manufacture',
        'price',
        'expiry_date',
        'quantity',
        'img_url',
        'category_id',

    ];
    protected $primaryKey = "id";
    public $timestamps = true;
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }


    //
    static function getMedsByCategories($category_id) {
//        return self::where("type" , $tid)->first();
        return self::where("category_id" , $category_id)->get();

    }
}
