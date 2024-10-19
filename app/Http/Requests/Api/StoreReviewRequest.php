<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ValidationResponse;
class StoreReviewRequest extends FormRequest
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
            //
            'review_comment' => 'required|string',
            'review_rating' => 'required|integer',
            'provider_id'=> 'required|exists:providers,id',
            'service_id'=>'required|exists:services,id'
        ];
    }
}
