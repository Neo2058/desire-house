<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreLeadRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

        return [

            'name'=>[
                'nullable',
                'string',
                'max:255'
            ],


            'phone'=>[
                'required',
                'string',
                'max:50'
            ],


            'object_type'=>[
                'nullable',
                'string',
                'max:255'
            ],


            'message'=>[
                'nullable',
                'string'
            ],


        ];

    }

}
