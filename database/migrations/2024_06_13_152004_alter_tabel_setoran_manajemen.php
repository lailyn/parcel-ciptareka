<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setoranManajemen_detail', function (Blueprint $table) {
            $table->uuid('id',50)->primary();              
            $table->string('code')->nullable();                                              
            $table->integer('setoranPaket_id');                                                        
            $table->integer('created_by')->nullable();                        
            $table->integer('updated_by')->nullable();                        
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('setoranManajemen_detail');
    }
};
