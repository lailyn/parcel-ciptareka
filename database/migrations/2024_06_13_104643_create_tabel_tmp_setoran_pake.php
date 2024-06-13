<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setoranPaket_tmp', function (Blueprint $table) {
            $table->uuid('id',50)->primary();                                    
            $table->integer('member_paket_id');            
            $table->integer('partnership_id')->nullable();                        
            $table->integer('created_by')->nullable();                        
            $table->integer('updated_by')->nullable();                        
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('setoranPaket_tmp');
    }
};
