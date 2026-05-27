#!/bin/bash

echo "========================================"
echo "   МАССОВЫЙ ИМПОРТ ИЗ STEAM"
echo "========================================"

LIMIT=200

genres=(
    "Action"
    "Adventure"
    "Strategy"
    "Simulation"
    "Sports"
    "Indie"
    "Casual"
    "MMO"
    "Racing"
    "Fighting"
    "Puzzle"
    "Platformer"
    "Shooter"
    "RPG"
    "Survival"
    "Horror"
    "Open World"
    "Sandbox"
    "Building"
    "Management"
    "Educational"
    "Arcade"
    "Visual Novel"
    "Card Game"
    "Battle Royale"
    "MOBA"
    "Rhythm"
    "Fitness"
)

for genre in "${genres[@]}"; do
    echo ""
    echo "📥 Импортирую жанр: $genre (лимит: $LIMIT)"
    php artisan games:import --source=steam --genre="$genre" --limit=$LIMIT
    sleep 2
done

echo ""
echo "========================================"
echo "   ✅ ИМПОРТ ЗАВЕРШЁН!"
echo "========================================"
