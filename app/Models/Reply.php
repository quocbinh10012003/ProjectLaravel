<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','comment_id','content','notify','user_id_reply'];
    protected $table = 'replies';
    public function comment()
    {
        return $this->belongsTo(Comment::class,'comment_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function user_reply()
    {
        return $this->belongsTo(User::class,'user_id_reply','id');
    }
}
