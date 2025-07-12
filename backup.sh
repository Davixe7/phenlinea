#!/bin/bash

# Script para hacer dump de MySQL excluyendo ciertas tablas

# Configuración
USUARIO="root"
BASE_DATOS="phenlinea"
ARCHIVO_DUMP="backup.sql"

# Opciones comunes
OPCIONES="-t"

# Tablas a ignorar
IGNORE_TABLES=(
  "sessions"
  "visits"
  "visitors"
  "migrations"
  "resident_invoices"
  "resident_invoice_items"
  "resident_invoice_payments"
  "resident_invoice_item_resident_invoice_payment"
)

# Construir opciones de --ignore-table
for table in "${IGNORE_TABLES[@]}"; do
  OPCIONES+=" --ignore-table=${BASE_DATOS}.${table}"
done

# Ejecutar mysqldump
echo "Dumping base de datos '$BASE_DATOS', excluyendo tablas ignoradas..."
mysqldump -u "$USUARIO" -p "$OPCIONES" "$BASE_DATOS" > "$ARCHIVO_DUMP"

echo "Backup completado: $ARCHIVO_DUMP"
