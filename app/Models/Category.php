<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['category_name','img_category'];
    protected $table = 'categories';
    public function food()
    {   
        return $this->hasMany(Food::class)->select('category_name');
    }
}
