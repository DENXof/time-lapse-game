<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            if (Schema::hasColumn('games', 'manual_price')) {
                $table->dropColumn('manual_price');
            }
            if (Schema::hasColumn('games', 'prices')) {
                $table->dropColumn('prices');
            }
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->string('manual_price')->nullable();
            $table->json('prices')->nullable();
        });
    }
};
