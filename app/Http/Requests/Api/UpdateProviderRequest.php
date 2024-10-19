<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidationResponse;

class UpdateProviderRequest extends FormRequest
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
        $provider = $this->user('providers');
        return [
            'name' => 'string|max:255',
            'email' => "max:100|unique:customers,email,$provider->id",
            // 'password' => 'required|string|min:6|max:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'bio'=> 'nullable|string'
        ];
    }
}
