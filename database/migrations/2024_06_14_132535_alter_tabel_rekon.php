<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('periode_rekonsiliasi', function (Blueprint $table) {                      
            $table->string('id',50)->change();            
            $table->string('code')->nullable()->after("id");            
        });
    }    
    public function down()
    {
        Schema::table('periode_rekonsiliasi', function (Blueprint $table) {          
          $table->integer('id')->change();
          $table->dropColumn('code');                                        
        });
    }
};
