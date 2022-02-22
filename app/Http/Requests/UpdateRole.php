<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRole extends FormRequest
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
            //'name'        => Rule::unique('roles')->ignore($this->role->id,'id'),
            //'name'        => 'unique:roles,name,' . $this->role['id'],
            'name'        => 'unique:roles,name,' . request()->segment(4),
            'permissions' => 'required|array'
        ];
    }
}
