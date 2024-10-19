<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidationResponse;
use Illuminate\Validation\Rule;

class StoreProviderRequest extends FormRequest
{
    use ValidationResponse;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
//            'last_name' => 'required|string|max:255',
//            'company_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('providers')], //unique:users,email
            'phone' => ['required', 'string', "min:10", "max:20", Rule::unique('providers')],
            'password' => 'required|string|min:6|max:8',
            'country' => "required|string",
            'zone_id' => "required|integer",
        ];
    }
}
