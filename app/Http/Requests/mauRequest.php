<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class mauRequest extends FormRequest
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

    public function rules()
    {
        return [
            'ten_mau'   => 'required|unique:mau_sac,ten_mau',
            'ma_mau'    =>['required','unique:mau_sac,hex','regex:/(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))/i','unique:mau_sac,hex'],

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
            'regex'         =>  ':attribute không đúng định dạng',
        ];
    }

    public function attributes()
    {
        return [
            'ten_mau'      =>  'Màu',
            'ma_mau'      =>  'Mã màu',
        ];
    }
}
