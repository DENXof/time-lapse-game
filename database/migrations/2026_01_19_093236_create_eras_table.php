<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('eras', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('start_year');
            $table->integer('end_year');
            $table->text('description');
            $table->text('characteristics');
            $table->string('color_primary');
            $table->string('color_secondary');
            $table->string('font_family')->default('monospace');
            $table->string('background_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eras');
    }
};
