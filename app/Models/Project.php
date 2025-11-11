<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'creator_id',
        'project_name',
        'description',
        'status',
        'start_date',
        'end_date',
    ];
}
