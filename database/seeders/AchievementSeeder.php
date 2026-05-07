<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        $achievements = [
            // Оценки
            [
                'name' => 'Первый шаг',
                'slug' => 'first-rating',
                'description' => 'Поставить первую оценку игре',
                'icon' => 'fas fa-star',
                'points' => 10,
                'type' => 'rating',
                'required_count' => 1
            ],
            [
                'name' => 'Любитель',
                'slug' => 'rating-10',
                'description' => 'Оценить 10 игр',
                'icon' => 'fas fa-star-half-alt',
                'points' => 25,
                'type' => 'rating',
                'required_count' => 10
            ],
            [
                'name' => 'Знаток',
                'slug' => 'rating-50',
                'description' => 'Оценить 50 игр',
                'icon' => 'fas fa-star',
                'points' => 50,
                'type' => 'rating',
                'required_count' => 50
            ],
            [
                'name' => 'Гуру',
                'slug' => 'rating-100',
                'description' => 'Оценить 100 игр',
                'icon' => 'fas fa-crown',
                'points' => 100,
                'type' => 'rating',
                'required_count' => 100
            ],

            // Избранное
            [
                'name' => 'Коллекционер',
                'slug' => 'favorite-5',
                'description' => 'Добавить 5 игр в избранное',
                'icon' => 'fas fa-heart',
                'points' => 15,
                'type' => 'favorite',
                'required_count' => 5
            ],
            [
                'name' => 'Библиотекарь',
                'slug' => 'favorite-20',
                'description' => 'Добавить 20 игр в избранное',
                'icon' => 'fas fa-book',
                'points' => 40,
                'type' => 'favorite',
                'required_count' => 20
            ],
            [
                'name' => 'Энтузиаст',
                'slug' => 'favorite-50',
                'description' => 'Добавить 50 игр в избранное',
                'icon' => 'fas fa-trophy',
                'points' => 80,
                'type' => 'favorite',
                'required_count' => 50
            ],

            // Комментарии
            [
                'name' => 'Говорун',
                'slug' => 'comment-1',
                'description' => 'Написать первый комментарий',
                'icon' => 'fas fa-comment',
                'points' => 10,
                'type' => 'comment',
                'required_count' => 1
            ],
            [
                'name' => 'Дискуссионщик',
                'slug' => 'comment-10',
                'description' => 'Написать 10 комментариев',
                'icon' => 'fas fa-comments',
                'points' => 30,
                'type' => 'comment',
                'required_count' => 10
            ],
            [
                'name' => 'Оратор',
                'slug' => 'comment-50',
                'description' => 'Написать 50 комментариев',
                'icon' => 'fas fa-microphone-alt',
                'points' => 70,
                'type' => 'comment',
                'required_count' => 50
            ],

            // Лайки комментариев
            [
                'name' => 'Критик',
                'slug' => 'likes-10',
                'description' => 'Получить 10 лайков на комментариях',
                'icon' => 'fas fa-thumbs-up',
                'points' => 25,
                'type' => 'likes',
                'required_count' => 10
            ],
            [
                'name' => 'Эксперт',
                'slug' => 'likes-50',
                'description' => 'Получить 50 лайков на комментариях',
                'icon' => 'fas fa-award',
                'points' => 60,
                'type' => 'likes',
                'required_count' => 50
            ],
            [
                'name' => 'Легенда',
                'slug' => 'likes-200',
                'description' => 'Получить 200 лайков на комментариях',
                'icon' => 'fas fa-medal',
                'points' => 150,
                'type' => 'likes',
                'required_count' => 200
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['slug' => $achievement['slug']],
                $achievement
            );
        }
    }
}
