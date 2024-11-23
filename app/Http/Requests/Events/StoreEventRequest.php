<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'title'         => 'required',
            'description'   => 'required',
            'date'          => 'required',
            'location'      => 'required',

            // Validation for ticket-availability fields
            'ticket_type_id'   => 'required|array',
            'ticket_type_id.*' => 'required',

            'total_tickets'    => 'required|array',
            'total_tickets.*'  => 'required|integer|min:1',

            'sold_tickets'     => 'required|array',
            'sold_tickets.*'   => 'required|integer|min:0',

            'price'            => 'required|array',
            'price.*'          => 'required|numeric|min:0',

        ];
    }
}
