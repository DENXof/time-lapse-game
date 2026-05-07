<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Название достижения
            $table->string('slug')->unique(); // Уникальный идентификатор
            $table->text('description');      // Описание
            $table->string('icon');            // Иконка (CSS класс или путь)
            $table->integer('points');         // Очки за достижение
            $table->string('type');            // Тип: rating, favorite, comment, etc.
            $table->integer('required_count'); // Необходимое количество действий
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('achievements');
    }
};
