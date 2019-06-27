<?php


namespace V1\Services\Tip\Requests;


use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'perPage' => 'required_with:paginate|numeric|min:1',
            'page' => 'required_with:paginate|numeric|min:1',
            'paginate' => 'bool'
        ];
    }
}
