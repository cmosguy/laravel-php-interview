<?php


namespace V1\Services\Tip\Requests;


use Illuminate\Foundation\Http\FormRequest;

class NewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:255|string',
            'description' => 'required|max:1000|string'
        ];
    }
}
