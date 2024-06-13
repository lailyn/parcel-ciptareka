<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('setoranPaket', function (Blueprint $table) {                      
            $table->integer('lama')->after("nominal")->nullable();                                                  
            $table->string('tgl_bayar')->after("tgl_setor")->nullable();                                                  
        });
    }    
    public function down()
    {
        Schema::table('setoranPaket', function (Blueprint $table) {          
          $table->dropColumn('lama');                                        
          $table->dropColumn('tgl_bayar');                                        
        });
    }
};
