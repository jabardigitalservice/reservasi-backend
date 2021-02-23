<?php

namespace App\Http\Requests;

use App\Enums\AssetStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumValueRule;

class StoreAssetRequest extends FormRequest
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
            'name' => 'required|unique:assets,name,NULL,id,deleted_at,NULL',
            'status' => ['required', new EnumValueRule(AssetStatusEnum::class)],
            'capacity' => 'required|numeric'
        ];
    }
}
