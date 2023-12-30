<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "categories";
    protected $fillable = [
        'title',
        'img_url'
    ];
    protected $primaryKey = "id";
    public $timestamps = true;

    public function medications() {
        return $this->hasMany(Medication::class);
    }
}
