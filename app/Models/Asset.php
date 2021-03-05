<?php

namespace App\Models;

use App\Enums\AssetStatusEnum;
use App\Enums\ResourceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'status', 'capacity', 'resource_type', 'description'];

    protected $enums = [
        'status' => AssetStatusEnum::class,
        'resource_type' => ResourceType::class,
    ];
}
