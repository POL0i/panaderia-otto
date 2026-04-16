

<?php $__env->startSection('title', 'Editar Empleado'); ?>
<?php $__env->startSection('page-title', 'Editar Empleado'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card animate-fade-in-up">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit mr-2"></i>
                        Editar Empleado
                    </h3>
                </div>
                <form action="<?php echo e(route('empleados.update', $empleado->id_empleado)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nombre">
                                <i class="fas fa-user mr-2 text-primary"></i>
                                Nombre
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nombre" name="nombre" value="<?php echo e($empleado->nombre); ?>" placeholder="Ingrese nombre" required>
                            <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="invalid-feedback d-block"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label for="apellido">
                                <i class="fas fa-user mr-2 text-primary"></i>
                                Apellido
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control <?php $__errorArgs = ['apellido'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="apellido" name="apellido" value="<?php echo e($empleado->apellido); ?>" placeholder="Ingrese apellido" required>
                            <?php $__errorArgs = ['apellido'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="invalid-feedback d-block"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="telefono">
                                    <i class="fas fa-phone mr-2 text-primary"></i>
                                    Teléfono
                                </label>
                                <input type="tel" class="form-control <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="telefono" name="telefono" value="<?php echo e($empleado->telefono); ?>" placeholder="Ingrese teléfono">
                                <?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="invalid-feedback d-block"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="fecha_nac">
                                    <i class="fas fa-calendar mr-2 text-primary"></i>
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" class="form-control <?php $__errorArgs = ['fecha_nac'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="fecha_nac" name="fecha_nac" value="<?php echo e($empleado->fecha_nac ? $empleado->fecha_nac->format('Y-m-d') : ''); ?>">
                                <?php $__errorArgs = ['fecha_nac'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="invalid-feedback d-block"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="direccion">
                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                Dirección
                            </label>
                            <input type="text" class="form-control <?php $__errorArgs = ['direccion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="direccion" name="direccion" value="<?php echo e($empleado->direccion); ?>" placeholder="Ingrese dirección">
                            <?php $__errorArgs = ['direccion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <small class="invalid-feedback d-block"><?php echo e($message); ?></small>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="sueldo">
                                    <i class="fas fa-dollar-sign mr-2 text-primary"></i>
                                    Sueldo
                                </label>
                                <input type="number" class="form-control <?php $__errorArgs = ['sueldo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="sueldo" name="sueldo" value="<?php echo e($empleado->sueldo); ?>" placeholder="0.00" step="0.01" min="0">
                                <?php $__errorArgs = ['sueldo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="invalid-feedback d-block"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="edad">
                                    <i class="fas fa-birthday-cake mr-2 text-primary"></i>
                                    Edad
                                </label>
                                <input type="number" class="form-control <?php $__errorArgs = ['edad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="edad" name="edad" value="<?php echo e($empleado->edad); ?>" placeholder="0" min="0" max="120">
                                <?php $__errorArgs = ['edad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="invalid-feedback d-block"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-check mr-2"></i>
                            Actualizar Empleado
                        </button>
                        <a href="<?php echo e(route('empleados.index')); ?>" class="btn btn-back">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/empleados/edit.blade.php ENDPATH**/ ?>