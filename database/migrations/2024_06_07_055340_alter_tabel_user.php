<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {                      
            $table->string('jenis')->after("id_user_type")->nullable();                                                  
            $table->string('partnership_id')->after("jenis")->nullable();                                                  
        });
    }    
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {          
          $table->dropColumn('jenis');                                        
          $table->dropColumn('partnership_id');                                        
        });
    }
};
