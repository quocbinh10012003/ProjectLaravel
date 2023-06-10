<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ratting extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','food_id','rate','content'];
    protected $table = 'rattings';
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function food()
    {
        return $this->belongsTo(Food::class,'food_id','id');
    }
}
