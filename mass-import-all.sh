#!/bin/bash

echo "========================================"
echo "   МАССОВЫЙ ИМПОРТ ИГР (STEAM + IGDB)"
echo "========================================"

LIMIT=200

# Steam жанры
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
    "Puzzle"
    "Platformer"
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
)

echo ""
echo "🟢 ИМПОРТ ИЗ STEAM:"
echo "========================================"

for genre in "${steam_genres[@]}"; do
    echo ""
    echo "📥 Steam: $genre (лимит: $LIMIT)"
    php artisan games:import --source=steam --genre="$genre" --limit=$LIMIT
    sleep 2
done

echo ""
echo "🟣 ИМПОРТ ИЗ IGDB:"
echo "========================================"

for search in "${igdb_searches[@]}"; do
    echo ""
    echo "🔍 IGDB: $search (лимит: $LIMIT)"
    php artisan games:import --source=igdb --search="$search" --limit=$LIMIT
    sleep 2
done

echo ""
echo "========================================"
echo "   ✅ ВСЕ ИМПОРТЫ ЗАВЕРШЕНЫ!"
echo "========================================"
