<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
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
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'event_id'       => 'required|exists:events,id',
            'user_id'        => 'required|exists:users,id',
            'quantity'       => 'required|integer|min:1',
        ];
    }
}
