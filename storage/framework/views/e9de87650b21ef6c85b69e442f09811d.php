<?php $__env->startSection('title', 'Editar Lote'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-pencil-alt icon-panaderia"></i> Editar Lote #<?php echo e($lote->id_lote); ?></h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('lotes.index')); ?>" class="btn btn-back btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-edit icon-panaderia"></i> Formulario de Edición</h5>
        </div>
        <form action="<?php echo e(route('lotes.update', $lote)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="cantidad_disponible"><i class="fas fa-calculator icon-panaderia"></i> Cantidad Disponible <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad_disponible" id="cantidad_disponible" step="0.01" class="form-control <?php $__errorArgs = ['cantidad_disponible'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('cantidad_disponible', $lote->cantidad_disponible)); ?>" max="<?php echo e($lote->cantidad_inicial); ?>" placeholder="0.00">
                        <small class="form-text text-muted">Máximo: <?php echo e($lote->cantidad_inicial); ?></small>
                        <?php $__errorArgs = ['cantidad_disponible'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="metodo_valuacion"><i class="fas fa-list icon-panaderia"></i> Método de Valuación <span class="text-danger">*</span></label>
                        <select name="metodo_valuacion" id="metodo_valuacion" class="form-control <?php $__errorArgs = ['metodo_valuacion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="PEPS" <?php echo e(old('metodo_valuacion', $lote->metodo_valuacion) == 'PEPS' ? 'selected' : ''); ?>>PEPS</option>
                            <option value="UEPS" <?php echo e(old('metodo_valuacion', $lote->metodo_valuacion) == 'UEPS' ? 'selected' : ''); ?>>UEPS</option>
                        </select>
                        <?php $__errorArgs = ['metodo_valuacion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="alert alert-info animate-fade-in">
                    <h6 class="font-weight-bold"><i class="fas fa-info-circle"></i> Información del Lote:</h6>
                    <ul class="mb-0">
                        <li><strong>Almacén:</strong> <?php echo e($lote->almacen->nombre ?? 'N/A'); ?></li>
                        <li><strong>Item:</strong> <?php echo e($lote->item->nombre ?? 'N/A'); ?></li>
                        <li><strong>Cantidad Inicial:</strong> <?php echo e($lote->cantidad_inicial); ?></li>
                        <li><strong>Precio Unitario:</strong> $<?php echo e(number_format($lote->precio_unitario, 2)); ?></li>
                        <li><strong>Fecha de Entrada:</strong> <?php echo e($lote->fecha_entrada->format('d/m/Y H:i')); ?></li>
                        <li><strong>Estado Actual:</strong> <?php echo e(ucfirst($lote->estado)); ?></li>
                    </ul>
                </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <button type="submit" class="btn btn-save btn-sm">
                <i class="fas fa-save"></i> Actualizar
            </button>
            <a href="<?php echo e(route('lotes.index')); ?>" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/inventario/lotes/edit.blade.php ENDPATH**/ ?>