#!/bin/bash

echo "========================================"
echo "   МАССОВЫЙ ИМПОРТ ИЗ IGDB (TWITCH)"
echo "========================================"

LIMIT=200

searches=(
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
    "racing"
    "fighting"
    "puzzle"
    "platformer"
    "shooter"
    "survival"
    "horror"
    "open world"
    "sandbox"
    "building"
)

for search in "${searches[@]}"; do
    echo ""
    echo "🔍 Импортирую: $search (лимит: $LIMIT)"
    php artisan games:import --source=igdb --search="$search" --limit=$LIMIT
    sleep 2
done

echo ""
echo "========================================"
echo "   ✅ ИМПОРТ ЗАВЕРШЁН!"
echo "========================================"

#!/bin/bash

echo "========================================"
echo "   МАССОВЫЙ ИМПОРТ ИЗ IGDB (TWITCH)"
echo "========================================"

LIMIT=200

searches=(
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
    "racing"
    "fighting"
    "puzzle"
    "platformer"
    "shooter"
    "survival"
    "horror"
    "open world"
    "sandbox"
    "building"
)

for search in "${searches[@]}"; do
    echo ""
    echo "🔍 Импортирую: $search (лимит: $LIMIT)"
    php artisan games:import --source=igdb --search="$search" --limit=$LIMIT
    sleep 2
done

echo ""
echo "========================================"
echo "   ✅ ИМПОРТ ЗАВЕРШЁН!"
echo "========================================"

#!/bin/bash

echo "========================================"
echo "   МАССОВЫЙ ИМПОРТ ИЗ IGDB (TWITCH)"
echo "========================================"

LIMIT=200

searches=(
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
    "racing"
    "fighting"
    "puzzle"
    "platformer"
    "shooter"
    "survival"
    "horror"
    "open world"
    "sandbox"
    "building"
)

for search in "${searches[@]}"; do
    echo ""
    echo "🔍 Импортирую: $search (лимит: $LIMIT)"
    php artisan games:import --source=igdb --search="$search" --limit=$LIMIT
    sleep 2
done

echo ""
echo "========================================"
echo "   ✅ ИМПОРТ ЗАВЕРШЁН!"
echo "========================================"
