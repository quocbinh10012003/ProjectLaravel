<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','food_id','quantity','total','price'];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function food()
    {
        return $this->hasMany(Food::class,'id','id');
    }  
}
