<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\AssetStatusEnum;
use Spatie\Enum\Laravel\Rules\EnumRule;

class StoreAsetRequest extends FormRequest
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
            'name' => 'required',
            'status' => ['required', new EnumRule(AssetStatusEnum::class)],
            'description' => 'required'
        ];
    }
}
