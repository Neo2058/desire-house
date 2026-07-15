<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::table('leads', function (Blueprint $table) {


            /*
             |--------------------------------------------------------------------------
             | Данные клиента
             |--------------------------------------------------------------------------
             */

            $table->string('object_type')
                ->nullable()
                ->after('phone');


            /*
             |--------------------------------------------------------------------------
             | CRM статус
             |--------------------------------------------------------------------------
             */

            $table->string('status')
                ->default('new')
                ->after('source');


            $table->boolean('processed')
                ->default(false)
                ->after('status');


            $table->text('admin_comment')
                ->nullable()
                ->after('processed');


        });

    }


    public function down(): void
    {

        Schema::table('leads', function (Blueprint $table) {


            $table->dropColumn([

                'object_type',

                'status',

                'processed',

                'admin_comment',

            ]);


        });

    }

};
