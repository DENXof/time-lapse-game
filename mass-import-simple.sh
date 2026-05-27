#!/bin/bash

echo "========================================"
echo "   МАССОВЫЙ ИМПОРТ ИГР ИЗ STEAM"
echo "========================================"

LIMIT=100

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

#!/bin/bash

echo "========================================"
echo "   МАССОВЫЙ ИМПОРТ ИГР ИЗ STEAM"
echo "========================================"

LIMIT=100

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
