<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Path extends Model
{

    protected $table = 'paths';
    public $timestamps = true;
    protected $fillable = [
        'userId',
        'path'
    ];
}
