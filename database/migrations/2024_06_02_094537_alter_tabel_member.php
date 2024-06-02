<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('member', function (Blueprint $table) {          
          $table->date('tgl_lahir')->after("no_ktp")->nullable();                                                  
        });
    }    
    public function down()
    {
        Schema::table('member', function (Blueprint $table) {          
          $table->dropColumn('tgl_lahir');                                        
        });
    }
};
