<?php

namespace App\Http\Requests;

use Auth;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|between:2,25|unique:users,name,'.Auth::id(),
            'introduction' => 'max:80',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => '用户名已被占用，请重新填写',
            'name.between' => '用户名必须介于 2 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
        ];
    }
}
