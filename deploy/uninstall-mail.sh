#!/usr/bin/env bash
# ============================================================
# uninstall-mail.sh — Elimina el stack de correo completamente
# Uso: bash deploy/uninstall-mail.sh
# ============================================================
set -euo pipefail

DEPLOY_DIR="$(cd "$(dirname "$0")" && pwd)"

echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║   Panadería Otto — Desinstalar Correo        ║"
echo "╚══════════════════════════════════════════════╝"
echo ""
echo "⚠️  Esto elimina Roundcube, Postfix, Dovecot y sus contenedores."
read -p "¿Continuar? [s/N]: " resp
[[ "$resp" =~ ^[sS]$ ]] || { echo "Cancelado."; exit 0; }

cd "$DEPLOY_DIR"

echo "🛑 Deteniendo contenedores de correo..."
docker compose -f docker-compose-mail.yml down --remove-orphans 2>/dev/null || true

echo "🗑️  Eliminando imágenes..."
docker rmi ghcr.io/docker-mailserver/docker-mailserver roundcube/roundcubemail 2>/dev/null || true

read -p "¿Borrar también los correos almacenados (volúmenes)? [s/N]: " resp2
if [[ "$resp2" =~ ^[sS]$ ]]; then
    docker volume rm \
        panaderia_mail_data \
        panaderia_mail_state \
        panaderia_mail_logs \
        panaderia_mail_config \
        panaderia_roundcube_db \
        panaderia_roundcube_temp 2>/dev/null || true
    echo "✅ Datos de correo eliminados"
else
    echo "✅ Datos de correo conservados"
fi

docker network rm panaderia_mail_net 2>/dev/null || true

echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║   ✅ Servidor de correo desinstalado         ║"
echo "║   Sin rastros en el sistema                  ║"
echo "╚══════════════════════════════════════════════╝"
