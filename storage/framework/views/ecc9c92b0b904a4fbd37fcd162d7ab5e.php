<?php if(count($cart) > 0): ?>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead style="background: #D2B48C;">
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="<?php echo e($item['imagen'] ? asset('storage/' . $item['imagen']) : 'https://placehold.co/50x50/8B4513/white?text=Pan'); ?>" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-right: 10px;">
                        <div>
                            <strong><?php echo e($item['nombre']); ?></strong><br>
                            <small class="text-muted">Almacén: <?php echo e($item['almacen_nombre']); ?></small>
                        </div>
                    </div>
                </td>
                <td>Bs. <?php echo e(number_format($item['precio'], 2)); ?></td>
                <td>
                    <input type="number" 
                           value="<?php echo e($item['cantidad']); ?>" 
                           min="1" 
                           class="form-control form-control-sm cart-quantity-input" 
                           style="width: 80px;"
                           onchange="actualizarCantidad('<?php echo e($key); ?>', this.value)">
                </td>
                <td>Bs. <?php echo e(number_format($item['precio'] * $item['cantidad'], 2)); ?></td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="eliminarProducto('<?php echo e($key); ?>')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr style="background: #FFF5E6;">
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td colspan="2"><strong style="font-size: 1.2rem; color: #8B4513;">Bs. <?php echo e(number_format($total, 2)); ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>
<?php else: ?>
<div class="text-center py-5">
    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
    <p class="text-muted">Tu carrito está vacío</p>
    <button class="btn" style="background: #8B4513; color: white;" data-bs-dismiss="modal">Seguir Comprando</button>
</div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\panaderia-otto\resources\views/carrito.blade.php ENDPATH**/ ?>