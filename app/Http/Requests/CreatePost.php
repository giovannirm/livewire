<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePost extends FormRequest
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
            'title' => 'required|max:100',
            'content' => 'required|max:200',
            'image' => 'required|image|mimes:jpg,bmp,jpeg,png|max:2048',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'título',
            'content' => 'contenido',
            'image' => 'imagen',
        ];
    }
}
