<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('setoranManajemen_detail', function (Blueprint $table) {                      
            $table->string('setoranPaket_id',50)->change();            
        });
    }    
    public function down()
    {
        Schema::table('setoranManajemen_detail', function (Blueprint $table) {          
          $table->integer('setoranPaket_id')->change();
        });
    }
};
