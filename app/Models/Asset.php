<?php

namespace App\Models;

use App\Enums\AssetStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'status', 'description'];

    protected $enums = [
        'status' => AssetStatusEnum::class,
    ];
}
