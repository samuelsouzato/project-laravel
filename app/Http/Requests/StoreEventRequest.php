<?php

namespace App\Http\Requests;

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
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'items' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => 'A imagem é obrigatória.',
            'image.image' => 'O arquivo deve ser uma imagem.',
            'image.mimes' => 'A imagem deve estar no formato JPG, JPEG ou PNG.',
            'title.required' => 'Nome do evento é obrigatório.',
            'date.required' =>  'A data do evento é obrigatória.',
            'city.required' => 'A cidade é obrigatória.',
            'description.required' => 'A descrição é obrigatória.',
            'items.required' => 'Escolha pelo menos um item.',
            
        ];
    }
}
