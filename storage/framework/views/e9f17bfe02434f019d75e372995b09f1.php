<?php $__env->startSection('title', 'Directorio de Personas'); ?>
<?php $__env->startSection('page-title', 'Directorio de Personas'); ?>
<?php $__env->startSection('page-description', 'Empleados y clientes registrados'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .info-box {
        border-radius: var(--border-radius-md);
        transition: transform 0.2s ease;
    }
    .info-box:hover { transform: translateY(-2px); }
    .info-box .info-box-icon {
        border-radius: var(--border-radius-sm);
        width: 60px;
    }
    .info-box .info-box-text {
        color: var(--text-muted);
        font-size: 0.85rem;
    }
    .info-box .info-box-number {
        color: var(--color-primary-dark);
        font-weight: 700;
        font-size: 1.3rem;
    }

    .info-box-primary .info-box-icon { background: var(--color-primary); }
    .info-box-success .info-box-icon { background: var(--badge-success); }
    .info-box-info .info-box-icon    { background: var(--badge-info); }
    .info-box-warning .info-box-icon { background: var(--badge-warning); }

    .filter-btn-group .btn {
        border-radius: 20px;
        margin-right: 2px;
        margin-bottom: 2px;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
    .filter-btn-group .btn:hover { transform: translateY(-1px); }
    .filter-btn-group .btn.active { font-weight: 600; }

    .persona-row-warning {
        background-color: rgba(255, 193, 7, 0.06);
    }
    .persona-tipo-badge {
        font-size: 0.8rem;
        padding: 0.3rem 0.6rem;
        border-radius: 15px;
        color: white;
    }
    .persona-tipo-empleado { background: var(--color-primary); }
    .persona-tipo-cliente  { background: var(--badge-info); }
    .persona-usuario-si { background: var(--badge-success); color: white; }
    .persona-usuario-no { background: var(--badge-danger); color: white; }

    .card-header-dark {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-dark .card-title { color: var(--text-on-primary); }

    .table-personas th, .table-personas td {
        vertical-align: middle;
        font-size: 0.85rem;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row mb-3">
        <div class="col-md-3 col-6">
            <div class="info-box info-box-primary bg-panaderia-light shadow-sm">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total</span>
                    <span class="info-box-number"><?php echo e($total); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-box info-box-success bg-panaderia-light shadow-sm">
                <span class="info-box-icon"><i class="fas fa-user-tie"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Empleados</span>
                    <span class="info-box-number"><?php echo e($empleadosCount); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-box info-box-info bg-panaderia-light shadow-sm">
                <span class="info-box-icon"><i class="fas fa-user"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes</span>
                    <span class="info-box-number"><?php echo e($clientesCount); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-box info-box-warning bg-panaderia-light shadow-sm">
                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Sin Usuario</span>
                    <span class="info-box-number"><?php echo e($sinUsuario); ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="filter-btn-group">
                        <a href="<?php echo e(route('personas.index', ['filtro' => 'todos', 'buscar' => $buscar])); ?>" 
                           class="btn btn-sm btn-outline-secondary <?php echo e($filtro == 'todos' ? 'active' : ''); ?>">
                            Todos
                        </a>
                        <a href="<?php echo e(route('personas.index', ['filtro' => 'empleados', 'buscar' => $buscar])); ?>" 
                           class="btn btn-sm btn-outline-primary <?php echo e($filtro == 'empleados' ? 'active' : ''); ?>">
                            Empleados
                        </a>
                        <a href="<?php echo e(route('personas.index', ['filtro' => 'clientes', 'buscar' => $buscar])); ?>" 
                           class="btn btn-sm btn-outline-info <?php echo e($filtro == 'clientes' ? 'active' : ''); ?>">
                            Clientes
                        </a>
                        <a href="<?php echo e(route('personas.index', ['filtro' => 'sin_usuario', 'buscar' => $buscar])); ?>" 
                           class="btn btn-sm btn-outline-warning <?php echo e($filtro == 'sin_usuario' ? 'active' : ''); ?>">
                            Sin usuario
                        </a>
                        <a href="<?php echo e(route('personas.index', ['filtro' => 'con_usuario', 'buscar' => $buscar])); ?>" 
                           class="btn btn-sm btn-outline-success <?php echo e($filtro == 'con_usuario' ? 'active' : ''); ?>">
                            Con usuario
                        </a>
                    </div>
                </div>
                <div class="col-md-4 mt-2 mt-md-0">
                    <form method="GET" action="<?php echo e(route('personas.index')); ?>" class="input-group input-group-sm">
                        <input type="hidden" name="filtro" value="<?php echo e($filtro); ?>">
                        <input type="text" name="buscar" class="form-control" 
                               placeholder="Buscar..." value="<?php echo e($buscar); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                            <?php if($buscar): ?>
                                <a href="<?php echo e(route('personas.index', ['filtro' => $filtro])); ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header card-header-dark">
            <h3 class="card-title">
                <i class="fas fa-address-book mr-2"></i> Listado de Personas (<?php echo e($total); ?>)
            </h3>
            <div class="card-tools">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#createEmpleadoModal">
                    <i class="fas fa-plus mr-1"></i> Empleado
                </button>
                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#createClienteModal">
                    <i class="fas fa-plus mr-1"></i> Cliente
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0 table-personas">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Contacto</th>
                            <th>Info Extra</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $personas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $persona): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="<?php echo e(!$persona['tiene_usuario'] ? 'persona-row-warning' : ''); ?>">
                                
                                <td>
                                    <span class="persona-tipo-badge persona-tipo-<?php echo e(strtolower($persona['tipo'])); ?>">
                                        <i class="fas <?php echo e($persona['icono_tipo']); ?> mr-1"></i>
                                        <?php echo e($persona['tipo']); ?>

                                    </span>
                                </td>
                                
                                
                                <td><strong><?php echo e($persona['nombre']); ?></strong></td>
                                
                                
                                <td>
                                    <?php if($persona['telefono']): ?>
                                        <i class="fas fa-phone text-muted mr-1"></i> <?php echo e($persona['telefono']); ?>

                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                    <?php if($persona['direccion']): ?>
                                        <br><small class="text-muted"><i class="fas fa-map-marker-alt"></i> <?php echo e($persona['direccion']); ?></small>
                                    <?php endif; ?>
                                </td>
                                
                                
                                <td>
                                    <small class="text-muted"><?php echo e($persona['info_extra'] ?: '-'); ?></small>
                                </td>
                                
                                
                                <td>
                                    <?php if($persona['tiene_usuario']): ?>
                                        <span class="badge persona-usuario-si">Sí</span>
                                        <br><small class="text-muted"><?php echo e($persona['usuario_correo']); ?></small>
                                    <?php else: ?>
                                        <span class="badge persona-usuario-no">No</span>
                                    <?php endif; ?>
                                </td>
                                
                                
                                <td>
                                    <?php if(!$persona['tiene_usuario']): ?>
                                        <button class="btn btn-primary btn-sm crear-usuario-btn"
                                                data-tipo="<?php echo e(strtolower($persona['tipo'])); ?>"
                                                data-id="<?php echo e($persona['id']); ?>"
                                                data-nombre="<?php echo e($persona['nombre']); ?>">
                                            <i class="fas fa-user-plus"></i> Crear Usuario
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">Asignado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    No se encontraron personas
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>


<?php echo $__env->make('usuarios.partials.modal-create-empleado', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('usuarios.partials.modal-create-cliente', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('usuarios.partials.modal-create-usuario', [
    'empleados' => $empleados ?? \App\Models\Empleado::all(),
    'clientes' => $clientes ?? \App\Models\Cliente::all()
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Al hacer clic en "Crear Usuario", abrir el modal con datos prellenados
    $(document).on('click', '.crear-usuario-btn', function() {
        const tipo = $(this).data('tipo');
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        
        $('#tipo_usuario').val(tipo).trigger('change');
        $('#createUsuarioModal').modal('show');
        
        if (tipo === 'empleado') {
            $('#id_empleado').val(id);
        } else if (tipo === 'cliente') {
            $('#id_cliente').val(id);
        }
        
        // Prellenar correo sugerido
        const nombreLimpio = nombre.toLowerCase().replace(/\s+/g, '.');
        $('#correo').val(nombreLimpio + '@panaderia.com');
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/usuarios/personas.blade.php ENDPATH**/ ?>