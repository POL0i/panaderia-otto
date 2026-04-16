

<?php $__env->startSection('title', 'Crear Proveedor'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-truck icon-panaderia"></i> Crear Nuevo Proveedor</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('proveedores.index')); ?>" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="fas fa-exclamation-circle"></i> Errores de validación</h5>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Formulario de Proveedor</h5>
        </div>
        <form action="<?php echo e(route('proveedores.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="card-body">
                <div class="form-group">
                    <label for="tipo_proveedor"><i class="fas fa-list icon-panaderia"></i> Tipo de Proveedor <span class="text-danger">*</span></label>
                    <select class="form-control <?php $__errorArgs = ['tipo_proveedor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tipo_proveedor" name="tipo_proveedor" required onchange="toggleFields()">
                        <option value="">Seleccione tipo</option>
                        <option value="persona" <?php echo e(old('tipo_proveedor') === 'persona' ? 'selected' : ''); ?>>Persona</option>
                        <option value="empresa" <?php echo e(old('tipo_proveedor') === 'empresa' ? 'selected' : ''); ?>>Empresa</option>
                    </select>
                    <?php $__errorArgs = ['tipo_proveedor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group" id="nombre_field" style="display: none;">
                    <label for="nombre"><i class="fas fa-user icon-panaderia"></i> Nombre</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nombre" name="nombre" value="<?php echo e(old('nombre')); ?>" placeholder="Nombre del proveedor">
                    <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group" id="razon_social_field" style="display: none;">
                    <label for="razon_social"><i class="fas fa-building icon-panaderia"></i> Razón Social</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['razon_social'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="razon_social" name="razon_social" value="<?php echo e(old('razon_social')); ?>" placeholder="Razón social de la empresa">
                    <?php $__errorArgs = ['razon_social'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label for="correo"><i class="fas fa-envelope icon-panaderia"></i> Correo <span class="text-danger">*</span></label>
                    <input type="email" class="form-control <?php $__errorArgs = ['correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="correo" name="correo" value="<?php echo e(old('correo')); ?>" placeholder="ejemplo@email.com" required>
                    <?php $__errorArgs = ['correo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label for="telefono"><i class="fas fa-phone icon-panaderia"></i> Teléfono <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="telefono" name="telefono" value="<?php echo e(old('telefono')); ?>" placeholder="+56 9 1234 5678" required>
                    <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label for="direccion"><i class="fas fa-map-marker-alt icon-panaderia"></i> Dirección <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['direccion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="direccion" name="direccion" value="<?php echo e(old('direccion')); ?>" placeholder="Calle, número, ciudad" required>
                    <?php $__errorArgs = ['direccion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="<?php echo e(route('proveedores.index')); ?>" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
<script>
function toggleFields() {
    const tipo = document.getElementById('tipo_proveedor').value;
    document.getElementById('nombre_field').style.display = tipo === 'persona' ? 'block' : 'none';
    document.getElementById('razon_social_field').style.display = tipo === 'empresa' ? 'block' : 'none';
}
toggleFields();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/proveedores/create.blade.php ENDPATH**/ ?>