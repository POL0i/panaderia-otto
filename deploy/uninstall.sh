#!/usr/bin/env bash
# ============================================================
# uninstall.sh — Detiene y elimina TODO Docker de este proyecto
# Uso: bash deploy/uninstall.sh
# ============================================================
set -euo pipefail

DEPLOY_DIR="$(cd "$(dirname "$0")" && pwd)"

echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║   Panadería Otto — Desinstalador             ║"
echo "╚══════════════════════════════════════════════╝"
echo ""
echo "⚠️  Esto eliminará los contenedores, imágenes y redes."
echo "   Los datos de MySQL se conservan a menos que elijas borrarlos."
echo ""
read -p "¿Continuar? [s/N]: " resp
[[ "$resp" =~ ^[sS]$ ]] || { echo "Cancelado."; exit 0; }

cd "$DEPLOY_DIR"

# ── Detener y eliminar contenedores ──
echo "🛑 Deteniendo contenedores..."
docker compose --env-file ../.env down --remove-orphans 2>/dev/null || true

# ── Eliminar imágenes del proyecto ──
echo "🗑️  Eliminando imágenes..."
docker rmi panaderia_php panaderia_nginx 2>/dev/null || true
docker image prune -f 2>/dev/null || true

# ── Preguntar si borrar volúmenes (datos de BD) ──
echo ""
read -p "¿Borrar también los datos de la base de datos (volúmenes)? [s/N]: " resp2
if [[ "$resp2" =~ ^[sS]$ ]]; then
    docker volume rm panaderia_mysql_data panaderia_redis_data \
                     panaderia_nginx_logs panaderia_php_logs 2>/dev/null || true
    echo "✅ Volúmenes eliminados (datos de BD borrados)"
else
    echo "✅ Volúmenes conservados (datos de BD intactos)"
fi

# ── Eliminar red ──
docker network rm panaderia_net 2>/dev/null || true

# ── Preguntar si desinstalar Docker ──
echo ""
read -p "¿Desinstalar Docker completamente del sistema? [s/N]: " resp3
if [[ "$resp3" =~ ^[sS]$ ]]; then
    sudo apt-get purge -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
    sudo rm -rf /var/lib/docker /etc/docker
    sudo groupdel docker 2>/dev/null || true
    echo "✅ Docker desinstalado del sistema"
fi

echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║   ✅ Desinstalación completa                 ║"
echo "║   Tu Linux Mint está limpio                  ║"
echo "╚══════════════════════════════════════════════╝"
