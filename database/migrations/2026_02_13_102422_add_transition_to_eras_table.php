<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('eras', function (Blueprint $table) {
            // Добавляем поле transition для хранения информации о переходе к следующей эпохе
            if (!Schema::hasColumn('eras', 'transition')) {
                $table->text('transition')->nullable()->after('characteristics');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eras', function (Blueprint $table) {
            if (Schema::hasColumn('eras', 'transition')) {
                $table->dropColumn('transition');
            }
        });
    }
};
