<?php

namespace App\Http\Requests;

use App\Enums\AssetStatusEnum;
use App\Enums\ResourceTypeEnum;
use Spatie\Enum\Laravel\Rules\EnumValueRule;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class EditAssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:assets,name,' . $this->asset->id . ',id,deleted_at,NULL',
            'status' => ['required', new EnumRule(AssetStatusEnum::class)],
            'capacity' => 'required|numeric',
            'resource_type' => ['required', new EnumValueRule(ResourceTypeEnum::class)],
            'description' => 'nullable'
        ];
    }
}
