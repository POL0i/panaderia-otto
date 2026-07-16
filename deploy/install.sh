#!/usr/bin/env bash
# ============================================================
# install.sh — Instala Docker y lanza Panadería Otto
# Uso: bash deploy/install.sh
# ============================================================
set -euo pipefail

PROYECTO_DIR="$(cd "$(dirname "$0")/.." && pwd)"
DEPLOY_DIR="$PROYECTO_DIR/deploy"
DOMINIO="panaderia-otto.shop"

echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║   Panadería Otto — Instalador de servidor    ║"
echo "╚══════════════════════════════════════════════╝"
echo ""

# ── 1. Verificar que corremos como usuario normal con sudo ──
if [ "$EUID" -eq 0 ]; then
    echo "❌ No corras este script como root. Usa tu usuario normal."
    exit 1
fi

# ── 2. Instalar Docker si no está ──
if ! command -v docker &>/dev/null; then
    echo "📦 Instalando Docker..."
    curl -fsSL https://get.docker.com | sh
    sudo usermod -aG docker "$USER"
    echo "✅ Docker instalado. Necesitas cerrar sesión y volver a entrar."
    echo "   Luego vuelve a correr este script."
    exit 0
else
    echo "✅ Docker ya instalado: $(docker --version)"
fi

if ! command -v docker &>/dev/null || ! docker compose --env-file ../.env version &>/dev/null; then
    echo "❌ Docker Compose no disponible. Instala Docker Desktop o el plugin."
    exit 1
fi

# ── 3. Verificar .env ──
if [ ! -f "$PROYECTO_DIR/.env" ]; then
    echo "⚠️  No existe .env. Copiando desde .env.example..."
    cp "$PROYECTO_DIR/.env.example" "$PROYECTO_DIR/.env"
    echo ""
    echo "🔧 EDITA el archivo .env antes de continuar:"
    echo "   nano $PROYECTO_DIR/.env"
    echo ""
    echo "   Variables importantes:"
    echo "   APP_URL=https://$DOMINIO"
    echo "   DB_PASSWORD=una_contraseña_segura"
    echo "   DB_ROOT_PASSWORD=otra_contraseña_root"
    echo ""
    read -p "¿Ya editaste el .env? [s/N]: " resp
    [[ "$resp" =~ ^[sS]$ ]] || { echo "Edita el .env y vuelve a correr el script."; exit 1; }
fi

# ── 4. Permisos de storage ──
echo "🔐 Configurando permisos de storage..."
sudo chown -R "$USER":www-data "$PROYECTO_DIR/storage" "$PROYECTO_DIR/bootstrap/cache" 2>/dev/null || true
chmod -R 775 "$PROYECTO_DIR/storage" "$PROYECTO_DIR/bootstrap/cache"

# ── 5. Obtener certificado SSL con Certbot (si no existe) ──
SSL_DIR="$DEPLOY_DIR/nginx/ssl"
if [ ! -f "$SSL_DIR/fullchain.pem" ]; then
    echo ""
    echo "🔒 Obteniendo certificado SSL para $DOMINIO..."
    echo "   (Tu IP pública debe estar apuntando a este equipo)"
    echo ""

    if ! command -v certbot &>/dev/null; then
        echo "📦 Instalando Certbot..."
        sudo apt-get update -q && sudo apt-get install -y certbot
    fi

    # Parar nginx si hay algo corriendo en puerto 80
    sudo fuser -k 80/tcp 2>/dev/null || true

    sudo certbot certonly --standalone \
        -d "$DOMINIO" -d "www.$DOMINIO" \
        --non-interactive --agree-tos \
        --email "admin@$DOMINIO" \
        --preferred-challenges http

    # Copiar certificados al directorio de nginx
    sudo cp /etc/letsencrypt/live/$DOMINIO/fullchain.pem "$SSL_DIR/"
    sudo cp /etc/letsencrypt/live/$DOMINIO/privkey.pem   "$SSL_DIR/"
    sudo chown "$USER":"$USER" "$SSL_DIR"/*.pem
    echo "✅ Certificado SSL obtenido"
else
    echo "✅ Certificado SSL ya existe"
fi

# ── 6. Construir y levantar contenedores ──
echo ""
echo "🐳 Construyendo y levantando contenedores..."
cd "$DEPLOY_DIR"
docker compose --env-file ../.env build --no-cache php
docker compose --env-file ../.env up -d

echo ""
echo "⏳ Esperando que MySQL esté listo..."
sleep 15

# ── 7. Instalar dependencias dentro del contenedor PHP ──
echo "📦 Instalando dependencias de Composer..."
docker compose --env-file ../.env exec php composer install --no-dev --optimize-autoloader

echo "📦 Compilando assets con Vite..."
docker compose --env-file ../.env exec php npm ci
docker compose --env-file ../.env exec php npm run build

# ── 8. Configurar Laravel ──
echo "⚙️  Configurando Laravel..."
docker compose --env-file ../.env exec php php artisan key:generate --force
docker compose --env-file ../.env exec php php artisan storage:link
docker compose --env-file ../.env exec php php artisan config:cache
docker compose --env-file ../.env exec php php artisan route:cache
docker compose --env-file ../.env exec php php artisan view:cache

# ── 9. Migrar base de datos ──
echo "🗄️  Ejecutando migraciones..."
docker compose --env-file ../.env exec php php artisan migrate --force

echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║   ✅ ¡Panadería Otto está en línea!          ║"
echo "║                                              ║"
echo "║   🌐 https://$DOMINIO          ║"
echo "╚══════════════════════════════════════════════╝"
echo ""
echo "Comandos útiles:"
echo "  Ver logs:      docker compose --env-file ../.env -f deploy/docker-compose.yml logs -f"
echo "  Detener:       docker compose --env-file ../.env -f deploy/docker-compose.yml stop"
echo "  Desinstalar:   bash deploy/uninstall.sh"
