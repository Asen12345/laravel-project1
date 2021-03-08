<?php

namespace App\Http\Requests\Front\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetLinkEmail extends FormRequest
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
            'email' => 'required|email|exists:users,email'
        ];
    }

    /**
     * Custom validation messages
     *
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'email.required' => 'Поле E-mail обязательно.',
            'email.exists' => 'Пользователь с таким email не найден.'
        ];
    }
}
