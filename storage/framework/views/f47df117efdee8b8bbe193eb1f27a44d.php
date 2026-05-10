<?php $__env->startSection('title', 'Configuración de Inventario'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-cog icon-panaderia"></i> Configuración de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="<?php echo e(route('movimientos.index')); ?>" class="btn btn-back btn-sm">
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

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Parámetros del Sistema</h5>
        </div>
        <form action="<?php echo e(route('configuracion.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="card-body">
                <div class="form-group">
                    <label for="metodo_valuacion_predeterminado"><i class="fas fa-list icon-panaderia"></i> Método de Valuación Predeterminado <span class="text-danger">*</span></label>
                    <select name="metodo_valuacion_predeterminado" id="metodo_valuacion_predeterminado" class="form-control <?php $__errorArgs = ['metodo_valuacion_predeterminado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="PEPS" <?php echo e($config->metodo_valuacion_predeterminado == 'PEPS' ? 'selected' : ''); ?>>
                            PEPS (Primero en Entrar, Primero en Salir)
                        </option>
                        <option value="UEPS" <?php echo e($config->metodo_valuacion_predeterminado == 'UEPS' ? 'selected' : ''); ?>>
                            UEPS (Último en Entrar, Primero en Salir)
                        </option>
                    </select>
                    <small class="form-text text-muted">
                        Este método se utilizará por defecto al crear nuevos lotes.
                    </small>
                    <?php $__errorArgs = ['metodo_valuacion_predeterminado'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <hr>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="automatizar_movimientos" id="automatizar_movimientos" class="custom-control-input" value="1" <?php echo e($config->automatizar_movimientos ? 'checked' : ''); ?>>
                        <label class="custom-control-label" for="automatizar_movimientos">
                            <strong><i class="fas fa-magic icon-panaderia"></i> Automatizar Movimientos</strong>
                        </label>
                    </div>
                    <small class="form-text text-muted" style="display: block; margin-top: 5px;">
                        Si está habilitado, el sistema creará automáticamente movimientos de inventario cuando se registren compras, ventas o producciones.
                    </small>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="requerir_aprobacion" id="requerir_aprobacion" class="custom-control-input" value="1" <?php echo e($config->requerir_aprobacion ? 'checked' : ''); ?>>
                        <label class="custom-control-label" for="requerir_aprobacion">
                            <strong><i class="fas fa-check-square icon-panaderia"></i> Requerir Aprobación</strong>
                        </label>
                    </div>
                    <small class="form-text text-muted" style="display: block; margin-top: 5px;">
                        Si está habilitado, los movimientos y traspasos necesitarán aprobación antes de ser completados.
                    </small>
                </div>

                <hr>

                <div class="alert alert-info animate-fade-in">
                    <h6 class="font-weight-bold"><i class="fas fa-info-circle"></i> Información del Sistema</h6>
                    <ul class="mb-0">
                        <li><strong>Última actualización:</strong> <?php echo e($config->updated_at->format('d/m/Y H:i')); ?></li>
                        <li><strong>Creado:</strong> <?php echo e($config->created_at->format('d/m/Y H:i')); ?></li>
                    </ul>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="<?php echo e(route('movimientos.index')); ?>" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/inventario/configuracion/edit.blade.php ENDPATH**/ ?>