<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setoranPaket', function (Blueprint $table) {
            $table->uuid('id',50)->primary();                        
            $table->string('code')->nullable();
            $table->integer('member_paket_id');
            $table->date('tgl_setor')->nullable();
            $table->integer('nominal')->nullable();            
            $table->string('penerima_setoran')->nullable();            
            $table->string('keterangan')->nullable();            
            $table->integer('submit')->nullable();            
            $table->dateTime('setor_at')->nullable();            
            $table->integer('setor_by')->nullable();            
            $table->integer('created_by')->nullable();                        
            $table->integer('updated_by')->nullable();                        
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('setoranPaket');
    }
};
