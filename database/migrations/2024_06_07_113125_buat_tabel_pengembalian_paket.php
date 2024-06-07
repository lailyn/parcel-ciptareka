<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalianSetoran', function (Blueprint $table) {
            $table->uuid('id',50)->primary();                        
            $table->string('code')->nullable();
            $table->integer('member_paket_id');
            $table->date('tgl_pengembalian')->nullable();
            $table->integer('nominal')->nullable();            
            $table->integer('presentase_manajemen')->nullable();            
            $table->integer('presentase_partner')->nullable();            
            $table->string('penerima_pengembalian')->nullable();            
            $table->string('keterangan')->nullable();                        
            $table->integer('created_by')->nullable();                        
            $table->integer('updated_by')->nullable();                        
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pengembalianSetoran');
    }
};
