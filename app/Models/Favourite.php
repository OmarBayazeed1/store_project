<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    use HasFactory;
    protected $table = "favourites";
    protected $fillable = [
        'user_id',
        'medication_id'
    ];
    protected $primaryKey = "id";
    public $timestamps = true;

    public function medication() {
        return $this->belongsTo(Medication::class, 'medication_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
