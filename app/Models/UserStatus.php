<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];
    

    // The attributes that should be hidden for arrays (optional, you can remove this if you want)
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
