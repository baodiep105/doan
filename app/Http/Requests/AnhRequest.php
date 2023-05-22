<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnhRequest extends FormRequest
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
            'hinh_anh'      =>  'required',
            'id_san_pham'      =>  'required',
        ];
    }

    public function messages()
    {
        return [
            'required'      =>  ':attribute không được để trống',
            'max'           =>  ':attribute quá dài',
            'exists'        =>  ':attribute không tồn tại',
            'boolean'       =>  ':attribute chỉ được chọn True/False',
            'unique'        =>  ':attribute đã tồn tại',
        ];
    }

    public function attributes()
    {
        return [
            'hinh_anh'      =>  'Hình ảnh',
            'id_san_pham'     =>  'Tên sản phẩm',
        ];
    }
}
