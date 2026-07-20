<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{

    public function store(Request $request)
    {

        $data = $request->validate([

            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\p{L}\s\-]+$/u',
            ],

            'phone' => [
                'required',
                'regex:/^\+?[0-9\s\-\(\)]{10,20}$/',
            ],

            'object_type' => [
                'required',
                Rule::in([
                    'Дом',
                    'Фундамент',
                    'Баня',
                    'Гараж',
                    'Пристройка',
                    'Терраса',
                    'Другое',
                ]),
            ],

            'source' => [
                'nullable',
                'string',
                'max:50',
            ],

        ], [

            'name.required' => 'Введите имя.',

            'name.regex' => 'Имя должно содержать только буквы.',

            'phone.required' => 'Введите телефон.',

            'phone.regex' => 'Введите корректный номер телефона.',

        ]);


        Lead::create([

            'name' => $data['name'],

            'phone' => $data['phone'],

            'object_type' => $data['object_type'],

            'source' => $data['source'] ?? 'site',

            'status' => 'new',

            'processed' => false,

        ]);


        return response()->json([

            'success'=>true,

            'message'=>'Спасибо! Ваша заявка отправлена.'

        ]);

    }

}
