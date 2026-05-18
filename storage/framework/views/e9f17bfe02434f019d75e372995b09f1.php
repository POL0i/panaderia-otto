<?php $__env->startSection('title', 'Directorio de Personas'); ?>
<?php $__env->startSection('page-title', 'Directorio de Personas'); ?>
<?php $__env->startSection('page-description', 'Empleados y clientes registrados'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ==========================================
       ESTILOS ESPECÍFICOS DE PERSONAS
       ========================================== */
    
    /* Info boxes */
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

    /* Variantes de info-box */
    .info-box-primary .info-box-icon { background: var(--color-primary); }
    .info-box-success .info-box-icon { background: var(--badge-success); }
    .info-box-info .info-box-icon    { background: var(--badge-info); }
    .info-box-warning .info-box-icon { background: var(--badge-warning); }

    /* Filtros */
    .filter-card {
        border-radius: var(--border-radius-md);
    }
    .filter-btn-group .btn {
        border-radius: 20px;
        margin-right: 2px;
        margin-bottom: 2px;
        font-size: 0.8rem;
        transition: all 0.2s ease;
    }
    .filter-btn-group .btn:hover {
        transform: translateY(-1px);
    }
    .filter-btn-group .btn.active {
        font-weight: 600;
    }

    /* Tabla */
    .persona-row-warning {
        background-color: rgba(var(--badge-warning-rgb, 255, 152, 0), 0.08);
    }
    .persona-tipo-badge {
        font-size: 0.8rem;
        padding: 0.3rem 0.6rem;
        border-radius: 15px;
    }
    .persona-tipo-empleado { background: var(--color-primary); color: var(--text-on-primary); }
    .persona-tipo-cliente  { background: var(--badge-info); color: var(--text-on-primary); }
    .persona-usuario-si { background: var(--badge-success); color: white; }
    .persona-usuario-no { background: var(--badge-danger); color: white; }
    .persona-email-code {
        background: var(--color-bg-lighter);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.8rem;
    }
    .persona-accion-btn {
        white-space: nowrap;
    }
    .persona-empty-icon { color: var(--text-muted); }

    /* Card header oscuro personalizado */
    .card-header-dark {
        background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
        color: var(--text-on-primary);
    }
    .card-header-dark .card-title { color: var(--text-on-primary); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    
    
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="info-box info-box-primary bg-panaderia-light shadow-sm">
                <span class="info-box-icon">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total</span>
                    <span class="info-box-number"><?php echo e($total); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box info-box-success bg-panaderia-light shadow-sm">
                <span class="info-box-icon">
                    <i class="fas fa-user-tie"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Empleados</span>
                    <span class="info-box-number"><?php echo e($empleadosCount); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box info-box-info bg-panaderia-light shadow-sm">
                <span class="info-box-icon">
                    <i class="fas fa-user"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes</span>
                    <span class="info-box-number"><?php echo e($clientesCount); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box info-box-warning bg-panaderia-light shadow-sm">
                <span class="info-box-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Sin Usuario</span>
                    <span class="info-box-number"><?php echo e($sinUsuario); ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card filter-card mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="filter-btn-group">
                        <a href="<?php echo e(route('personas.index', ['filtro' => 'todos', 'buscar' => $buscar])); ?>" 
                           class="btn btn-sm btn-outline-secondary <?php echo e($filtro == 'todos' ? 'active' : ''); ?>">
                            <i class="fas fa-list mr-1"></i> Todos
                        </a>
                        <a href="<?php echo e(route('personas.index', ['filtro' => 'empleados', 'buscar' => $buscar])); ?>" 
                           class="btn btn-sm btn-outline-primary <?php echo e($filtro == 'empleados' ? 'active' : ''); ?>">
                            <i class="fas fa-user-tie mr-1"></i> Empleados
                        </a>
                        <a href="<?php echo e(route('personas.index', ['filtro' => 'clientes', 'buscar' => $buscar])); ?>" 
                           class="btn btn-sm btn-outline-info <?php echo e($filtro == 'clientes' ? 'active' : ''); ?>">
                            <i class="fas fa-user mr-1"></i> Clientes
                        </a>
                        <a href="<?php echo e(route('personas.index', ['filtro' => 'sin_usuario', 'buscar' => $buscar])); ?>" 
                           class="btn btn-sm btn-outline-warning <?php echo e($filtro == 'sin_usuario' ? 'active' : ''); ?>">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Sin usuario
                        </a>
                        <a href="<?php echo e(route('personas.index', ['filtro' => 'con_usuario', 'buscar' => $buscar])); ?>" 
                           class="btn btn-sm btn-outline-success <?php echo e($filtro == 'con_usuario' ? 'active' : ''); ?>">
                            <i class="fas fa-check mr-1"></i> Con usuario
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <form method="GET" action="<?php echo e(route('personas.index')); ?>" class="input-group input-group-sm">
                        <input type="hidden" name="filtro" value="<?php echo e($filtro); ?>">
                        <input type="text" name="buscar" class="form-control" 
                               placeholder="Buscar por nombre o teléfono..." value="<?php echo e($buscar); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
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
                <i class="fas fa-address-book mr-2"></i>
                Listado de Personas (<?php echo e($total); ?>)
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
                            <th style="width: 5%">#</th>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Info Extra</th>
                            <th>¿Usuario?</th>
                            <th>Email Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $personas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $persona): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="<?php echo e(!$persona['tiene_usuario'] ? 'persona-row-warning' : ''); ?>">
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <span class="persona-tipo-badge persona-tipo-<?php echo e(strtolower($persona['tipo'])); ?>">
                                        <i class="fas <?php echo e($persona['icono_tipo']); ?> mr-1"></i>
                                        <?php echo e($persona['tipo']); ?>

                                    </span>
                                </td>
                                <td><strong><?php echo e($persona['nombre']); ?></strong></td>
                                <td><?php echo e($persona['telefono']); ?></td>
                                <td><?php echo e($persona['direccion']); ?></td>
                                <td><?php echo e($persona['info_extra']); ?></td>
                                <td>
                                    <?php if($persona['tiene_usuario']): ?>
                                        <span class="badge persona-usuario-si">
                                            <i class="fas fa-check"></i> Sí
                                        </span>
                                    <?php else: ?>
                                        <span class="badge persona-usuario-no">
                                            <i class="fas fa-times"></i> No
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($persona['tiene_usuario']): ?>
                                        <code class="persona-email-code"><?php echo e($persona['usuario_correo']); ?></code>
                                        <br>
                                        <small class="text-muted"><?php echo e(ucfirst($persona['usuario_estado'])); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="persona-accion-btn">
                                    <?php if(!$persona['tiene_usuario']): ?>
                                        <button class="btn btn-primary btn-xs crear-usuario-btn"
                                                data-tipo="<?php echo e(strtolower($persona['tipo'])); ?>"
                                                data-id="<?php echo e($persona['id']); ?>"
                                                data-nombre="<?php echo e($persona['nombre']); ?>">
                                            <i class="fas fa-user-plus"></i> Crear Usuario
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">Ya asignado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-inbox persona-empty-icon mr-2"></i>
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

<?php $__env->startPush('styles'); ?>
<style>
    /* ... tus estilos actuales ... */
    
    /* Forzar scroll horizontal en la tabla */
    .table-responsive {
        -webkit-overflow-scrolling: touch;
    }
    
    /* Ancho mínimo para que todas las columnas se vean bien */
    .table-personas {
        min-width: 1100px;
    }
    
    /* Reducir padding en celdas para aprovechar espacio */
    .table-personas th,
    .table-personas td {
        padding: 0.5rem 0.6rem !important;
        font-size: 0.85rem;
        vertical-align: middle;
        white-space: nowrap;
    }
    
    /* Columna nombre: permitir wrap y darle un ancho máximo */
    .table-personas td:nth-child(3) {
        white-space: normal;
        max-width: 180px;
        min-width: 120px;
    }
    
    /* Columna dirección: wrap controlado */
    .table-personas td:nth-child(5) {
        white-space: normal;
        max-width: 150px;
    }
    
    /* Columna info extra: compacta */
    .table-personas td:nth-child(6) {
        max-width: 100px;
        white-space: normal;
        font-size: 0.8rem;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.adminlte', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /opt/lampp/htdocs/panaderia-otto/resources/views/usuarios/personas.blade.php ENDPATH**/ ?>