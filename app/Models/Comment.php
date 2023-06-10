<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','food_id','content', 'user_id_reply','notify','id_comment'];
    protected $table = 'comments';
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function food()
    {
        return $this->belongsTo(Food::class,'food_id','id');
    }
    public function reply()
    {
        return $this->hasMany(Reply::class,'comment_id','id');
    }
}
