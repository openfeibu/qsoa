<?php

namespace App\Http\Requests;

use App\Http\Requests\Request as FormRequest;
use Input;
use Illuminate\Validation\Rule;

class AirlineUserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        if ($this->isStore()) {
            return [
                'name' => 'required|string',
                'email' => 'required|unique:airline_users',
                'password' => 'required|string|min:6',
            ];
        }
        if ($this->isUpdate()) {
            $input = Input::all();
            return [
                'name' => 'required|string',
                'email' => [
                    'filled',
                    Rule::unique('airline_users')->where(function($query)use($input){
                        return $query->where('id','<>',$input['id']);
                    })
                ],
                'password' => 'nullable|string|min:6',
            ];
        }
    }

    public function messages(){
        return [
            'name.require' => '姓名不能为空',
            'email.unique' => '该邮箱已被注册',
            'password.require' => '密码不能为空',
            'password.min' => '密码不能少于六个字符串',
        ];
    }
}
