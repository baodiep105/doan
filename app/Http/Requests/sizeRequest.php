<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class sizeRequest extends FormRequest
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
            'size'   => 'required|unique:size,size|numeric|min:16|max:49',
        ];
    }

    public function messages()
    {
        return [
            'required'      =>  ':attribute không được để trống',
            'max'           =>  ':attribute phải nhỏ hơn 49',
            'exists'        =>  ':attribute không tồn tại',
            'boolean'       =>  ':attribute chỉ được chọn True/False',
            'unique'        =>  ':attribute đã tồn tại',
            'min'           =>  ':attribute phải lớn hơn 15'
        ];
    }

    public function attributes()
    {
        return [
            'size'      =>  ' size',

        ];
    }
}
