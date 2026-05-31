<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->enum('age_rating', ['0+', '6+', '12+', '16+', '18+'])->default('0+')->after('platform');
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('age_rating');
        });
    }
};
