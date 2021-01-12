<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'asset';
    
    protected $fillable = ['name', 'status', 'description'];
}
