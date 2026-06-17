#!/usr/bin/env bash
# ============================================================
# mail-add-user.sh — Agrega una cuenta de correo
# Uso: bash deploy/mail-add-user.sh usuario@panaderia-otto.shop
# ============================================================
set -euo pipefail

DEPLOY_DIR="$(cd "$(dirname "$0")" && pwd)"
EMAIL="${1:-}"

if [ -z "$EMAIL" ]; then
    echo -n "Email a crear (ej: pedro@panaderia-otto.shop): "
    read EMAIL
fi

echo -n "Contraseña para $EMAIL: "
read -s PASSWORD
echo ""

cd "$DEPLOY_DIR"
docker compose -f docker-compose-mail.yml exec mailserver \
    setup email add "$EMAIL" "$PASSWORD"

echo "✅ Cuenta $EMAIL creada"
echo ""
echo "Para listar todas las cuentas:"
echo "  docker compose -f deploy/docker-compose-mail.yml exec mailserver setup email list"
