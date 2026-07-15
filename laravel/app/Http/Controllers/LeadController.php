<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{

    public function store(Request $request)
    {

        $data = $request->validate([

            'name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'phone' => [
                'required',
                'string',
                'max:50'
            ],

            'object_type' => [
                'nullable',
                'string'
            ],

        ]);


        Lead::create([

            'name' => $data['name'] ?? null,

            'phone' => $data['phone'],

            'object_type' => $data['object_type'] ?? null,

            'source' => 'cta',

            'status' => 'new',

        ]);


        return response()->json([

            'success'=>true,

            'message'=>'Спасибо! Ваша заявка отправлена.'

        ]);

    }

}
