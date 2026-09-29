# Guía Camaleón: Añadir Nuevos Campos en Laravel

Esta guía detalla el proceso completo para añadir distintos tipos de datos (fecha, texto, double) a tablas existentes en Laravel, abarcando desde la base de datos hasta el frontend.

## 1. Crear las Migraciones (Base de Datos)

Para añadir nuevos campos a tablas que ya existen, no debemos modificar las migraciones originales, sino crear migraciones nuevas de modificación.

Ejecuta los siguientes comandos en tu terminal:

```bash
php artisan make:migration add_fecha_contrato_to_empleados_table --table=empleados
php artisan make:migration add_descripcion_to_producciones_table --table=producciones
php artisan make:migration add_monto_traspasado_to_traspasos_table --table=traspasos
```

Luego, edita cada archivo generado en la carpeta `database/migrations/`:

**Para la tabla Empleado (`...add_fecha_contrato_to_empleados_table.php`):**
```php
public function up() {
    Schema::table('empleados', function (Blueprint $table) {
        $table->date('fecha_contrato')->nullable()->after('apellido'); // Tipo Date
    });
}
public function down() {
    Schema::table('empleados', function (Blueprint $table) {
        $table->dropColumn('fecha_contrato');
    });
}
```

**Para la tabla Producción (`...add_descripcion_to_producciones_table.php`):**
```php
public function up() {
    Schema::table('producciones', function (Blueprint $table) {
        $table->text('descripcion')->nullable()->after('estado'); // Tipo Text
    });
}
public function down() {
    Schema::table('producciones', function (Blueprint $table) {
        $table->dropColumn('descripcion');
    });
}
```

**Para la tabla Traspaso (`...add_monto_traspasado_to_traspasos_table.php`):**
```php
public function up() {
    Schema::table('traspasos', function (Blueprint $table) {
        $table->double('monto_traspasado', 10, 2)->nullable()->after('id_almacen_destino'); // Tipo Double
    });
}
public function down() {
    Schema::table('traspasos', function (Blueprint $table) {
        $table->dropColumn('monto_traspasado');
    });
}
```

Finalmente, ejecuta: `php artisan migrate`.

---

## 2. Actualizar los Modelos

Debes permitir que estos nuevos campos se puedan guardar masivamente (Mass Assignment) agregándolos a la propiedad `$fillable`.

**En `app/Models/Empleado.php`:**
```php
protected $fillable = [
    // ... otros campos
    'fecha_contrato',
];

protected $casts = [
    'fecha_contrato' => 'date', // Cast automático
];
```

**En `app/Models/Produccion.php`:**
```php
protected $fillable = [
    // ... otros campos
    'descripcion',
];
```

**En `app/Models/Traspaso.php`:**
```php
protected $fillable = [
    // ... otros campos
    'monto_traspasado',
];

protected $casts = [
    'monto_traspasado' => 'double',
];
```

---

## 3. Actualizar los Controladores

En los métodos `store` (guardar) y `update` (actualizar) de cada controlador, debes agregar las validaciones correspondientes para los nuevos campos.

**En `EmpleadoController.php`:**
```php
$request->validate([
    // ... otras validaciones
    'fecha_contrato' => 'nullable|date',
]);
// El proceso de guardado (Empleado::create($request->all())) funcionará automáticamente si usas $fillable.
```

**En `ProduccionController.php`:**
```php
$request->validate([
    'descripcion' => 'nullable|string|max:1000',
]);
```

**En `TraspasoController.php`:**
```php
$request->validate([
    'monto_traspasado' => 'nullable|numeric|min:0',
]);
```

---

## 4. Implementación en el Frontend (Vistas de Blade)

Para que los usuarios puedan ingresar estos datos, debes añadir los `inputs` correspondientes en las vistas de creación y edición.

**En la vista de Empleados (`resources/views/empleados/create.blade.php`):**
```html
<div class="form-group">
    <label for="fecha_contrato">Fecha de Contrato</label>
    <input type="date" name="fecha_contrato" id="fecha_contrato" class="form-control" value="{{ old('fecha_contrato') }}">
</div>
```

**En la vista de Producciones (`resources/views/produccion/create.blade.php`):**
```html
<div class="form-group">
    <label for="descripcion">Descripción / Notas Adicionales</label>
    <textarea name="descripcion" id="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
</div>
```

**En la vista de Traspasos (`resources/views/inventario/traspasos/create.blade.php`):**
```html
<div class="form-group">
    <label for="monto_traspasado">Monto Traspasado (Bs.)</label>
    <input type="number" step="0.01" min="0" name="monto_traspasado" id="monto_traspasado" class="form-control" placeholder="0.00">
</div>
```
