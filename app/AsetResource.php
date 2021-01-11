<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AsetResource extends Model
{
    protected $fillable = ['name', 'status', 'description'];
}
