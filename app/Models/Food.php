<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;
    protected $fillable = ['name','img_food','detail','price','quantity','category_id'];
    protected $table = 'food';
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class,'food_id','id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class,'food_id','id');
    }
}
