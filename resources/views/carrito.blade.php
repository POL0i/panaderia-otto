@if(count($cart) > 0)
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
            @foreach($cart as $key => $item)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="{{ $item['imagen'] ? asset('storage/' . $item['imagen']) : 'https://placehold.co/50x50/8B4513/white?text=Pan' }}" 
                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; margin-right: 10px;">
                        <div>
                            <strong>{{ $item['nombre'] }}</strong><br>
                            <small class="text-muted">Almacén: {{ $item['almacen_nombre'] }}</small>
                        </div>
                    </div>
                </td>
                <td>Bs. {{ number_format($item['precio'], 2) }}</td>
                <td>
                        <input type="number" 
                            value="{{ $item['cantidad'] }}" 
                            min="1" 
                            class="form-control form-control-sm cart-quantity-input" 
                            style="width: 80px;"
                            onchange="actualizarCantidad('{{ $key }}', this.value)">
                </td>
                <td>Bs. {{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="eliminarProducto('{{ $key }}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #FFF5E6;">
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td colspan="2"><strong style="font-size: 1.2rem; color: #8B4513;">Bs. {{ number_format($total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
@else
<div class="text-center py-5">
    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
    <p class="text-muted">Tu carrito está vacío</p>
    <button class="btn" style="background: #8B4513; color: white;" data-bs-dismiss="modal">Seguir Comprando</button>
</div>
@endif