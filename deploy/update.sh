#!/usr/bin/env bash
# ============================================================
# update.sh — Actualiza el proyecto desde git y reinicia
# Uso: bash deploy/update.sh
# ============================================================
set -euo pipefail

PROYECTO_DIR="$(cd "$(dirname "$0")/.." && pwd)"
DEPLOY_DIR="$PROYECTO_DIR/deploy"

echo "🔄 Actualizando Panadería Otto..."

cd "$PROYECTO_DIR"

# Bajar cambios del repo
git pull origin main

# Reinstalar dependencias si cambiaron
cd "$DEPLOY_DIR"
docker compose exec php composer install --no-dev --optimize-autoloader
docker compose exec php npm ci
docker compose exec php npm run build

# Aplicar migraciones pendientes
docker compose exec php php artisan migrate --force

# Limpiar cachés
docker compose exec php php artisan config:cache
docker compose exec php php artisan route:cache
docker compose exec php php artisan view:cache

# Reiniciar PHP-FPM
docker compose restart php

echo "✅ Actualización completa"
