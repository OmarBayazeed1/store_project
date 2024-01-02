<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    //public $with=['category'];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function favourites(){
        return $this->hasMany(Favourite::class);
    }






    //
    static function getMedsByCategories($category_id) {
//        return self::where("type" , $tid)->first();
        return self::where("category_id" , $category_id)->get();

    }
}
