#!/bin/bash

echo "========================================"
echo "   МАССОВЫЙ ИМПОРТ ИГР (МАКСИМАЛЬНЫЙ)"
echo "========================================"

# МАКСИМАЛЬНЫЙ ЛИМИТ
LIMIT=500

# Steam жанры (некоторые могут вернуть меньше)
steam_genres=(
    "Action"
    "Adventure"
    "Strategy"
    "RPG"
    "Simulation"
    "Sports"
    "Indie"
    "Casual"
    "MMO"
    "Racing"
    "Fighting"
    "Shooter"
    "Survival"
    "Horror"
)

# IGDB поисковые запросы
igdb_searches=(
    "game"
    "rpg"
    "action"
    "adventure"
    "strategy"
    "simulation"
    "sports"
    "indie"
    "casual"
    "mmo"
    "open world"
    "horror"
    "fighting"
    "racing"
)

echo ""
echo "🟢 ИМПОРТ ИЗ STEAM (лимит: $LIMIT):"
echo "========================================"

for genre in "${steam_genres[@]}"; do
    echo ""
    echo "📥 Steam: $genre"
    php artisan games:import --source=steam --genre="$genre" --limit=$LIMIT
    sleep 3
done

echo ""
echo "🟣 ИМПОРТ ИЗ IGDB (лимит: $LIMIT):"
echo "========================================"

for search in "${igdb_searches[@]}"; do
    echo ""
    echo "🔍 IGDB: $search"
    php artisan games:import --source=igdb --search="$search" --limit=$LIMIT
    sleep 3
done

echo ""
echo "========================================"
echo "   ✅ ВСЕ ИМПОРТЫ ЗАВЕРШЕНЫ!"
echo "========================================"
