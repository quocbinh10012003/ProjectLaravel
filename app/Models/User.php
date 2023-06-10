<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function isAdmin()
    {
        return $this->role_as == 1; // Giả sử giá trị 1 đại diện cho vai trò admin
    }
    public function isUser()
    {
        return $this->role_as == 0; 
    }
    public function order()
    {
        return $this->hasMany(Order::class,'user_id','id')->select('id', 'name', 'email');
    }
    public function ratting()
    {
        return $this->hasMany(Ratting::class,'user_id','id')->select('id', 'name', 'email');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class,'user_id','id')->select('id', 'name', 'email');
    }
    public function replies()
    {
        return $this->hasMany(Comment::class,'user_id','id')->select('id', 'name', 'email');
    }
    public function messages()
    {
        return $this->hasMany(Message::class,'user_id','id')->select('id', 'name', 'email');
    }
}
