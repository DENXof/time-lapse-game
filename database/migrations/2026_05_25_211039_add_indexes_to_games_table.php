<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Индексы для таблицы games
        Schema::table('games', function (Blueprint $table) {
            if (!Schema::hasIndex('games', 'games_title_index')) {
                $table->index('title', 'games_title_index');
            }
            if (!Schema::hasIndex('games', 'games_release_year_index')) {
                $table->index('release_year', 'games_release_year_index');
            }
            if (!Schema::hasIndex('games', 'games_genre_id_index')) {
                $table->index('genre_id', 'games_genre_id_index');
            }
            if (!Schema::hasIndex('games', 'games_views_count_index')) {
                $table->index('views_count', 'games_views_count_index');
            }
            if (!Schema::hasIndex('games', 'games_rating_avg_index')) {
                $table->index('rating_avg', 'games_rating_avg_index');
            }
            if (!Schema::hasIndex('games', 'games_genre_id_release_year_index')) {
                $table->index(['genre_id', 'release_year'], 'games_genre_id_release_year_index');
            }
        });

        // Индексы для таблицы comments
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasIndex('comments', 'comments_game_id_index')) {
                $table->index('game_id', 'comments_game_id_index');
            }
            if (!Schema::hasIndex('comments', 'comments_user_id_index')) {
                $table->index('user_id', 'comments_user_id_index');
            }
            if (!Schema::hasIndex('comments', 'comments_parent_id_index')) {
                $table->index('parent_id', 'comments_parent_id_index');
            }
            if (!Schema::hasIndex('comments', 'comments_game_id_created_at_index')) {
                $table->index(['game_id', 'created_at'], 'comments_game_id_created_at_index');
            }
        });

        // Индексы для таблицы ratings
        Schema::table('ratings', function (Blueprint $table) {
            if (!Schema::hasIndex('ratings', 'ratings_user_id_index')) {
                $table->index('user_id', 'ratings_user_id_index');
            }
            if (!Schema::hasIndex('ratings', 'ratings_game_id_index')) {
                $table->index('game_id', 'ratings_game_id_index');
            }
            if (!Schema::hasIndex('ratings', 'ratings_game_id_value_index')) {
                $table->index(['game_id', 'value'], 'ratings_game_id_value_index');
            }
        });
    }

    public function down()
    {
        // Удаляем индексы для таблицы games
        Schema::table('games', function (Blueprint $table) {
            if (Schema::hasIndex('games', 'games_title_index')) {
                $table->dropIndex('games_title_index');
            }
            if (Schema::hasIndex('games', 'games_release_year_index')) {
                $table->dropIndex('games_release_year_index');
            }
            if (Schema::hasIndex('games', 'games_genre_id_index')) {
                $table->dropIndex('games_genre_id_index');
            }
            if (Schema::hasIndex('games', 'games_views_count_index')) {
                $table->dropIndex('games_views_count_index');
            }
            if (Schema::hasIndex('games', 'games_rating_avg_index')) {
                $table->dropIndex('games_rating_avg_index');
            }
            if (Schema::hasIndex('games', 'games_genre_id_release_year_index')) {
                $table->dropIndex('games_genre_id_release_year_index');
            }
        });

        // Удаляем индексы для таблицы comments
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasIndex('comments', 'comments_game_id_index')) {
                $table->dropIndex('comments_game_id_index');
            }
            if (Schema::hasIndex('comments', 'comments_user_id_index')) {
                $table->dropIndex('comments_user_id_index');
            }
            if (Schema::hasIndex('comments', 'comments_parent_id_index')) {
                $table->dropIndex('comments_parent_id_index');
            }
            if (Schema::hasIndex('comments', 'comments_game_id_created_at_index')) {
                $table->dropIndex('comments_game_id_created_at_index');
            }
        });

        // Удаляем индексы для таблицы ratings
        Schema::table('ratings', function (Blueprint $table) {
            if (Schema::hasIndex('ratings', 'ratings_user_id_index')) {
                $table->dropIndex('ratings_user_id_index');
            }
            if (Schema::hasIndex('ratings', 'ratings_game_id_index')) {
                $table->dropIndex('ratings_game_id_index');
            }
            if (Schema::hasIndex('ratings', 'ratings_game_id_value_index')) {
                $table->dropIndex('ratings_game_id_value_index');
            }
        });
    }
};
