<?php $__env->startSection('title', 'Crear Item'); ?>
<?php $__env->startSection('page-title', 'Crear Item'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Item</h3>
            <form action="<?php echo e(route('items.store')); ?>" method="POST" class="form-horizontal">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    <div class="form-group">
                        <label for="tipo_item">Tipo de Item</label>
                        <select class="form-control <?php $__errorArgs = ['tipo_item'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tipo_item" name="tipo_item" required>
                            <option value="">Seleccione tipo</option>
                            <option value="producto" <?php echo e(old('tipo_item') === 'producto' ? 'selected' : ''); ?>>Producto</option>
                            <option value="insumo" <?php echo e(old('tipo_item') === 'insumo' ? 'selected' : ''); ?>>Insumo</option>
                        </select>
                        <?php $__errorArgs = ['tipo_item'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small class="text-danger"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-group">
                        <label for="unidad_medida">Unidad de Medida</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['unidad_medida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="unidad_medida" name="unidad_medida" value="<?php echo e(old('unidad_medida')); ?>" required>
                        <?php $__errorArgs = ['unidad_medida'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <small class="text-danger"><?php echo e($message); ?></small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="<?php echo e(route('items.index')); ?>" class="btn btn-secondary">Cancelar</a>
            </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/item/create.blade.php ENDPATH**/ ?>