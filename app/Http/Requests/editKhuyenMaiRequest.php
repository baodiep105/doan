<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editKhuyenMaiRequest extends FormRequest
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
            'idEdit'                    =>'required',
            'id_san_pham_edit'          =>   'required|exists:san_phams,id|unique:khuyen_mai,id_san_pham,'.$this->idEdit,
            'ty_le_edit'                 =>'required|numeric|min:1|max:100',
            'is_open_edit'           =>   'required|boolean',

        ];
    }

    public function messages()
    {
        return [
            'required'      =>  ':attribute không được để trống',
            'max'           =>  ':attribute không được lớn hơn 100 ',
            'min'           =>  ':attribute không được nhỏ hơn 1',
            'digits_between'=>  ':attribute lớn hơn 1 và nhỏ hơn 100',
            'exists'        =>  ':attribute không tồn tại',
            'boolean'       =>  ':attribute chỉ được chọn True/False',
            'unique'        =>  ':attribute đã tồn tại',
            'numeric'       =>  ':attribute phải là số',
        ];
    }

    public function attributes()
    {
        return [
            'id_san_pham'=>'sản phẩm',
            'ty_le_edit'=>'tỷ lệ khuyến mãi',
            'trang_thai'=>'status',
        ];
    }
}
