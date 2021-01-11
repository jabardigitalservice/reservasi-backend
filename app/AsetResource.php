<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsetResource extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['name', 'status', 'description'];
}
