<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'food';
    protected $fillable = ['cat_id','name', 'description', 'price', 'image','discount'];
}
