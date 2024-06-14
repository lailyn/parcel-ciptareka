<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('setoranManajemen', function (Blueprint $table) {                      
            $table->integer('status')->after("nominal")->nullable();                                                  
            $table->integer('approval_status')->after("status")->nullable();                                                  
            $table->dateTime('approval_at')->after("approval_status")->nullable();                                                  
        });
    }    
    public function down()
    {
        Schema::table('setoranManajemen', function (Blueprint $table) {          
          $table->dropColumn('status');                                        
          $table->dropColumn('approval_status');                                        
          $table->dropColumn('approval_at');                                        
        });
    }
};
