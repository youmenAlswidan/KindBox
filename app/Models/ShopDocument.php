<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('shop_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops');
            $table->string('status');
            $table->string('document_type');
            $table->string('file_path_document');
            $table->timestamps();
        });
    }

   
    public function shop() {
        return $this->belongsTo(Shop::class);
    }
};