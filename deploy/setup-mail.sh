#!/usr/bin/env bash
# ============================================================
# setup-mail.sh — Configura el servidor de correo
# Corre DESPUÉS de install.sh (necesita el certificado SSL)
# Uso: bash deploy/setup-mail.sh
# ============================================================
set -euo pipefail

DEPLOY_DIR="$(cd "$(dirname "$0")" && pwd)"
DOMINIO="panaderia-otto.shop"
MAIL_HOST="correo.$DOMINIO"

echo ""
echo "╔══════════════════════════════════════════════╗"
echo "║   Panadería Otto — Configurar servidor correo ║"
echo "╚══════════════════════════════════════════════╝"
echo ""

cd "$DEPLOY_DIR"

# ── 1. Verificar que el certificado SSL existe ──
if [ ! -f "nginx/ssl/fullchain.pem" ]; then
    echo "❌ No existe el certificado SSL."
    echo "   Corre primero: bash deploy/install.sh"
    exit 1
fi

# ── 2. Obtener certificado para el subdominio de correo (si no existe) ──
echo "🔒 Obteniendo certificado SSL para $MAIL_HOST..."
if ! sudo certbot certificates 2>/dev/null | grep -q "$MAIL_HOST"; then
    sudo fuser -k 80/tcp 2>/dev/null || true
    sudo certbot certonly --standalone \
        -d "$MAIL_HOST" \
        --non-interactive --agree-tos \
        --email "admin@$DOMINIO"

    # Copiar a nginx/ssl (compartido con mailserver)
    sudo cp /etc/letsencrypt/live/$MAIL_HOST/fullchain.pem nginx/ssl/
    sudo cp /etc/letsencrypt/live/$MAIL_HOST/privkey.pem   nginx/ssl/
    sudo chown "$USER":"$USER" nginx/ssl/*.pem
    echo "✅ Certificado SSL para $MAIL_HOST obtenido"
else
    echo "✅ Certificado SSL ya existe"
fi

# ── 3. Levantar el stack de correo ──
echo ""
echo "🐳 Levantando contenedores de correo..."
docker compose -f docker-compose-mail.yml up -d

echo ""
echo "⏳ Esperando que el servidor de correo arranque (30s)..."
sleep 30

# ── 4. Crear cuentas de correo ──
echo ""
echo "📧 Creando cuentas de correo..."
echo "   (Se te pedirá una contraseña para cada cuenta)"
echo ""

create_account() {
    local email="$1"
    echo -n "   Contraseña para $email: "
    read -s password
    echo ""
    docker compose -f docker-compose-mail.yml exec mailserver \
        setup email add "$email" "$password"
    echo "   ✅ $email creado"
}

create_account "admin@$DOMINIO"
create_account "no-reply@$DOMINIO"

echo ""
echo "   ¿Deseas crear más cuentas? Puedes hacerlo luego con:"
echo "   bash deploy/mail-add-user.sh usuario@$DOMINIO"

# ── 5. Generar clave DKIM ──
echo ""
echo "🔑 Generando clave DKIM para firma de correos..."
docker compose -f docker-compose-mail.yml exec mailserver \
    setup config dkim

echo ""
echo "╔══════════════════════════════════════════════════════════╗"
echo "║   ✅ Servidor de correo configurado                      ║"
echo "║                                                          ║"
echo "║   Webmail: https://correo.$DOMINIO     ║"
echo "║   SMTP:    correo.$DOMINIO:587         ║"
echo "║   IMAP:    correo.$DOMINIO:993         ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo ""
echo "⚠️  IMPORTANTE — Agrega el registro DKIM a Spaceship:"
echo ""
docker compose -f docker-compose-mail.yml exec mailserver \
    cat /tmp/docker-mailserver/opendkim/keys/$DOMINIO/mail.txt 2>/dev/null \
    || echo "   (El archivo DKIM estará en deploy/mail_config/ después de reiniciar)"
echo ""
echo "Comandos útiles:"
echo "  Ver logs:       docker compose -f deploy/docker-compose-mail.yml logs -f mailserver"
echo "  Agregar cuenta: bash deploy/mail-add-user.sh"
echo "  Listar cuentas: docker compose -f deploy/docker-compose-mail.yml exec mailserver setup email list"
