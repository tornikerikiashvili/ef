<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_logos', function (Blueprint $table) {
            $table->id();
            $table->json('title')->nullable();
            $table->json('link')->nullable();
            $table->json('logo_white')->nullable();
            $table->json('logo_colorful')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_logos');
    }
};
